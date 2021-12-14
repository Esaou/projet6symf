<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
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
     * @param  Mailer                 $mailer
     * @param  Request                $request
     * @param  EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/register', name: 'register')]
    public function register(FileUpload $fileUpload,UserPasswordHasherInterface $passwordHasher,Mailer $mailer,Request $request,EntityManagerInterface $manager): Response
    {

        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(UserType::class,null,[
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

            $path = $fileUpload->upload($avatar,'avatars');

            $token = uniqid();

            $userEntity
                ->setSlug(uniqid())
                ->setAvatar($path)
                ->setPassword($password)
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
     * @param string                 $token
     * @param UserRepository         $userRepository

     * @param EntityManagerInterface $manager
     */
    #[Route('/user/confirm/{token}', name: 'user_confirm')]
    public function confirmUser(string $token, UserRepository $userRepository, EntityManagerInterface $manager): RedirectResponse
    {
        $user = $userRepository->findOneBy(['token'=>$token]);

        if ($user !== null) {
            $user->setIsValid(true);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Compte validé avec succès');
        } else {
            $this->addFlash('success', 'Erreur lors de la validation du compte');
        }

        return $this->redirectToRoute('app_login');
    }
}
