<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgottenPasswordType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{

    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/password/forgotten', name: 'forgotten_password')]
    public function forgottenPassword(Request $request,UserRepository $userRepository,Mailer $mailer) {

        $form = $this->createForm(ForgottenPasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $username = $form->get('username')->getData();

            $userEntity = $userRepository->findOneBy(['username'=>$username]);

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
    public function resetPassword(EntityManagerInterface $manager,UserPasswordHasherInterface $passwordHasher,Mailer $mailer,UserRepository $userRepository,Request $request,string $slug) {

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
            }

            $manager->flush();
        }

        return $this->render(
            'security/reset.html.twig', [
                'form' => $form->createView()
            ]
        );
    }
}
