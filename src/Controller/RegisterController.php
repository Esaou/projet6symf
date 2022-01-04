<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgottenPasswordType;
use App\Form\RegisterType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use App\Service\FileUpload;
use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegisterController extends AbstractController
{

    private TranslatorInterface $translator;

    private SluggerInterface $slugger;

    public function __construct(TranslatorInterface $translator,SluggerInterface $slugger)
    {
        $this->translator = $translator;
        $this->slugger = $slugger;
    }

    /**
     * @param FileUpload $fileUpload
     * @param UserPasswordHasherInterface $passwordHasher
     * @param Mailer $mailer
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     * @throws TransportExceptionInterface
     */
    #[Route('/register', name: 'register')]
    public function register(FileUpload $fileUpload,UserPasswordHasherInterface $passwordHasher,Mailer $mailer,Request $request,EntityManagerInterface $manager): Response
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
                $path = $fileUpload->upload($avatar,'avatars');
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

            $result = $mailer->mail('contact@snowtricks.com', $userEntity->getEmail(), 'Confirmation de compte', 'email/confirm.html.twig', ['user'=>$userEntity]);

            if ($result) {
                $this->addFlash('success', $this->translator->trans('register.flashSuccess'));
            } else {
                $this->addFlash('danger', $this->translator->trans('register.flashDanger'));
            }

        }

        return $this->render(
            'register/register.html.twig', [
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
    public function confirmUser(string|null $token, EntityManagerInterface $manager): RedirectResponse
    {

        if ($token === null) {
            $this->addFlash('danger', 'Token de validation invalide');
            return $this->redirectToRoute('app_login');
        }

        $user = $userRepository->findOneBy(['token'=>$token]);

        if ($user !== null) {
            $user->setToken(null);
            $user->setIsValid(true);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Compte validé avec succès');
        } else {
            $this->addFlash('danger', 'Token de validation invalide');
        }

        return $this->redirectToRoute('app_login');
    }

    #[Route('/password/forgotten', name: 'forgotten_password')]
    public function forgottenPassword(Request $request,UserRepository $userRepository,Mailer $mailer): RedirectResponse|Response
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

            $result = $mailer->mail('contact@snowtricks.com', $userEntity->getEmail(), 'Reinitialisation de mot de passe', 'email/reset.html.twig', ['user'=>$userEntity]);

            if ($result) {
                $this->addFlash('success', $this->translator->trans('forgotten.flashSuccess'));
            } else {
                $this->addFlash('danger', $this->translator->trans('forgotten.flashDanger'));
            }

        }

        return $this->render(
            'security/forgotten.html.twig', [
                'form' => $form->createView()
            ]
        );
    }

    #[Route('/reset/{slug}', name: 'reset_password')]
    public function resetPassword(EntityManagerInterface $manager,UserPasswordHasherInterface $passwordHasher, Request $request,string $slug): RedirectResponse|Response
    {

        $userRepository = $manager->getRepository(User::class);

        $user = $userRepository->findOneBy(['slug'=>$slug]);

        if ($user === null) {
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(ResetPasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var User $userEntity */
            $userEntity = $form->getData();

            if ($user->getUsername() === $form->get('username')->getData()) {
                $password = $passwordHasher->hashPassword($userEntity, $userEntity->getPassword());
                $user->setPassword($password);
                $manager->persist($user);
                $this->addFlash('success', $this->translator->trans('forgotten.flashSuccessReset'));
            } else {
                $this->addFlash('danger', $this->translator->trans('forgotten.flashDangerReset'));
                return $this->redirectToRoute('reset_password',['slug'=>$slug]);
            }

            $manager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render(
            'security/reset.html.twig', [
                'form' => $form->createView()
            ]
        );
    }


}
