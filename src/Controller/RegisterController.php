<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    #[Route('/register', name: 'register')]
    public function register(Request $request,EntityManagerInterface $manager): Response
    {

        $form = $this->createForm(UserType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /**
             * @var User $userEntity
             */
            $userEntity = $form->getData();

            $slug = $this->slugger->slug($userEntity->getUsername(), '_');
            $token = uniqid();

            $userEntity
                ->setSlug($slug)
                ->setAvatar()
                ->setIsValid(false)
                ->setToken($token);


            $manager->persist($userEntity);
            $manager->flush();

            $this->addFlash('success', $this->translator->trans('register.flashSuccess'));
        }

        return $this->render('register/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
