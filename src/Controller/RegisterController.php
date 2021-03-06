<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\MailEvent;
use App\Form\ForgottenPasswordType;
use App\Form\RegisterType;
use App\Form\ResetPasswordType;
use App\Form\SendConfirmationMailType;
use App\Repository\UserRepository;
use App\Service\FileUpload;
use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\UriSigner;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegisterController extends AbstractController
{
    private TranslatorInterface $translator;

    private UriSigner $signer;

    private EventDispatcherInterface $dispatcher;

    public function __construct(TranslatorInterface $translator, UriSigner $signer, EventDispatcherInterface $dispatcher)
    {
        $this->translator = $translator;
        $this->signer = $signer;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param FileUpload $fileUpload
     * @param UserPasswordHasherInterface $passwordHasher
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/register', name: 'register')]
    public function register(FileUpload $fileUpload, UserPasswordHasherInterface $passwordHasher, Request $request, EntityManagerInterface $manager): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(RegisterType::class,null,[
            'attr' => [
                'enctype' => 'multipart/form-data',
                'method' => 'POST'
            ]
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /**
             * @var User $userEntity
             */
            $userEntity = $form->getData();

            $password = $passwordHasher->hashPassword($userEntity, $userEntity->getPassword());

            /** @var UploadedFile $avatar */
            $avatar = $form->get('avatar')->getData();

            $path = null;

            if ($avatar !== null) {
                $path = (string)$fileUpload->upload($avatar,'avatars');
            }

            $token = uniqid();

            $userEntity
                ->setSlug(Uuid::v6())
                ->setAvatar($path)
                ->setPassword($password)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setIsValid(false)
                ->setToken($token);

            $manager->persist($userEntity);
            $manager->flush();

            /** @var Event $isMailSend */
            $isMailSend = $this->dispatcher->dispatch(new MailEvent($userEntity));

            if ($isMailSend->isPropagationStopped()) {
                return $this->redirectToRoute('home');
            }
        }

        return $this->render(
            'register/register.html.twig', [
            'form' => $form->createView()
            ]
        );
    }


    #[Route('/sendConfirmationMail', name: 'send_confirmation_mail')]
    public function sendConfirmationMail(Request $request,EntityManagerInterface $manager,Mailer $mailer,UserRepository $userRepository): Response
    {

        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(SendConfirmationMailType::class,null,[
            'attr' => [
                'method' => 'POST'
            ]
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $email = $form->get('email')->getData();

            /** @var User $userEntity */
            $userEntity = $userRepository->findOneBy(['email'=>$email]);

            if (null !== $userEntity) {
                $token = uniqid();

                $userEntity->setToken($token);
                $manager->persist($userEntity);
                $manager->flush();

                $result = $mailer->mail('contact@snowtricks.com', (string)$userEntity->getEmail(), 'Confirmation de compte', 'email/confirm.html.twig', ['user'=>$userEntity]);

                if ($result) {
                    $this->addFlash('success', $this->translator->trans('register.mailSendSuccess'));
                }

                if (!$result) {
                    $this->addFlash('danger',$this->translator->trans('register.flashDanger'));
                }
            }
        }

        return $this->render(
            'security/confirmationMail.html.twig', [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @param string|null $token
     * @param EntityManagerInterface $manager
     * @return RedirectResponse
     */
    #[Route('/confirm/{token}', name: 'user_confirm')]
    public function confirmUser(string|null $token, EntityManagerInterface $manager, Request $request): RedirectResponse
    {

        if (!$this->signer->checkRequest($request)) {
            $this->addFlash('danger', $this->translator->trans('register.url.invalid'));
            return $this->redirectToRoute('home');
        }

        $userRepository = $manager->getRepository(User::class);

        if ($token === null) {
            $this->addFlash('danger', $this->translator->trans('register.token.invalid'));
            return $this->redirectToRoute('home');
        }

        /** @var User $user */
        $user = $userRepository->findOneBy(['token'=>$token]);

        if ($user !== null) {

            if ($user->getCreatedAt()->modify('+ '.$this->getParameter('timerValidationLink').' day') < new \DateTimeImmutable('now')) {
                $this->addFlash('danger', $this->translator->trans('register.token.perim'));
                return $this->redirectToRoute('home',['perim'=>true]);
            }

            $user->setToken(null);
            $user->setIsValid(true);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success',$this->translator->trans('register.valid.account'));
        }

        return $this->redirectToRoute('home');
    }

    #[Route('/password/forgotten', name: 'forgotten_password')]
    public function forgottenPassword(Request $request, UserRepository $userRepository, Mailer $mailer, EntityManagerInterface $manager): RedirectResponse|Response
    {

        $form = $this->createForm(ForgottenPasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $username = $form->get('username')->getData();

            $userEntity = $userRepository->findOneBy(['username'=>$username]);

            if ($userEntity === null) {
                $this->addFlash('danger',$this->translator->trans('forgotten.flashNotFound'));
                return $this->redirectToRoute('forgotten_password');
            }

            $tokenReset = uniqid();
            $userEntity->setTokenReset($tokenReset);

            $manager->persist($userEntity);
            $manager->flush();

            $url = $this->generateUrl('reset_password',['slug'=>$userEntity->getSlug(),'tokenReset'=>$tokenReset],UrlGeneratorInterface::ABSOLUTE_URL);

            $url = $this->signer->sign($url);

            $result = $mailer->mail('contact@snowtricks.com', (string)$userEntity->getEmail(), 'Reinitialisation de mot de passe', 'email/reset.html.twig', ['user'=>$userEntity,'url'=>$url]);

            if ($result) {
                $this->addFlash('success', $this->translator->trans('forgotten.flashSuccess'));
            } else {
                $this->addFlash('danger',$this->translator->trans('forgotten.flashDanger'));
            }

            return $this->redirectToRoute('home');

        }

        return $this->render(
            'security/forgotten.html.twig', [
                'form' => $form->createView()
            ]
        );
    }

    #[Route('/reset/{slug}/{tokenReset}', name: 'reset_password')]
    public function resetPassword(EntityManagerInterface $manager,UserPasswordHasherInterface $passwordHasher, Request $request,string $slug, string $tokenReset): RedirectResponse|Response
    {
        if (!$this->signer->checkRequest($request)) {
            $this->addFlash('danger', $this->translator->trans('register.url.invalid.reset'));
            return $this->redirectToRoute('home');
        }

        $userRepository = $manager->getRepository(User::class);

        /** @var User $user */
        $user = $userRepository->findOneBy(['slug'=>$slug]);

        if ($tokenReset !== $user->getTokenReset()) {
            $this->addFlash('danger', $this->translator->trans('register.token.invalid.reset'));
            return $this->redirectToRoute('home');
        }

        if ($user === null) {
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(ResetPasswordType::class, null, [
            'attr' => [
                'method' => 'POST'
            ]
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $pass = (string)$form->get('password')->getData();

            if ($user->getUsername() === $pass) {
                $password = $passwordHasher->hashPassword($user, $pass);
                $user->setPassword($password);
                $user->setTokenReset(null);
                $manager->persist($user);
                $manager->flush();
                $this->addFlash('success',$this->translator->trans('forgotten.flashSuccessReset'));
                return $this->redirectToRoute('home');
            } else {
                $this->addFlash('danger', $this->translator->trans('forgotten.flashDangerReset'));
            }
        }

        return $this->render(
            'security/reset.html.twig', [
                'form' => $form->createView()
            ]
        );
    }


}
