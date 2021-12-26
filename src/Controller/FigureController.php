<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Image;
use App\Entity\Message;
use App\Entity\User;
use App\Entity\Video;
use App\Form\AddFigureType;
use App\Form\EditFigureType;
use App\Form\MessageType;
use App\Repository\FigureRepository;
use App\Repository\ImageRepository;
use App\Service\FileUpload;
use App\Service\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class FigureController extends AbstractController
{

    private TranslatorInterface $translator;

    private SluggerInterface $slugger;

    public function __construct(TranslatorInterface $translator,SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
        $this->translator = $translator;
    }

    #[Route('/figure/{slug}', name: 'figure')]
    public function show(string $slug,Request $request,EntityManagerInterface $manager,FigureRepository $figureRepository,Paginator $paginator): Response
    {

        $figure = $figureRepository->findOneBy(['slug'=>$slug]);

        $paginator->createPaginator(Message::class, ['figure'=>$figure], ['createdAt'=>'desc'],'figure', ['slug' => $slug],10);

        $form = $this->createForm(MessageType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var User $user */
            $user = $this->getUser();

            /**
 * @var Message $messageEntity 
*/
            $messageEntity = $form->getData();
            $messageEntity->setCreatedAt(new \DateTimeImmutable());
            $messageEntity->setUser($user);
            $messageEntity->setFigure($figure);

            $manager->persist($messageEntity);
            $manager->flush();

            $this->addFlash('success', $this->translator->trans('showFigure.confirmPost'));
        }

        return $this->render(
            'figure/show.html.twig', [
            'figure' => $figure,
            'paginator' => $paginator,
            'formMessage' => $form->createView()
            ]
        );
    }

    /**
     * @param Figure                 $figure
     * @param EntityManagerInterface $manager
     * @param FigureRepository       $figureRepository
     */
    #[Route('/user/{figure}/delete', name: 'delete_figure')]
    public function deleteFigure(EntityManagerInterface $manager, FigureRepository $figureRepository,Figure $figure = null)
    {
        $figure = $figureRepository->findOneBy(['id'=>$figure]);

        if ($figure !== null) {
            $manager->remove($figure);
            $manager->flush();
            $this->addFlash('danger', $this->translator->trans('figure.delete'));
        } else {
            $this->addFlash('danger', $this->translator->trans('figure.notfound'));

        }

        return $this->redirectToRoute('home');
    }


    /**
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    #[Route('/user/figure/add', name: 'add_figure')]
    public function add_figure(ImageRepository $imageRepository,FileUpload $fileUpload,Request $request,EntityManagerInterface $manager)
    {

        $form = $this->createForm(AddFigureType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /**
            *
             *
             * @var User $user
            */
            $user = $this->getUser();

            /**
             * @var Figure $figureEntity
             */
            $figureEntity = $form->getData();

            $slug = $this->slugger->slug($figureEntity->getName(), '_');
            $figureEntity
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUser($user)
                ->setSlug($slug);

            $manager->persist($figureEntity);
            $manager->flush();

            foreach ($form->get('images')->getData() as $image) {

                $path = $fileUpload->upload($image,'figures');

                $image = new Image();
                $image
                    ->setFilename($path)
                    ->setMain(false)
                    ->setFigure($figureEntity);

                $manager->persist($image);
                $manager->flush();
            }

            $main = current($imageRepository->findBy(['figure'=>$figureEntity]));
            $main->setMain(true);
            $manager->persist($main);
            $manager->flush();

            $this->addFlash('success', $this->translator->trans('editFigure.flashSuccess'));

            return $this->redirectToRoute('home');
        }

        return $this->render(
            'figure/add.html.twig', [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @param  Request                $request
     * @param  EntityManagerInterface $manager
     * @param  FigureRepository       $figureRepository
     * @param  string|null            $slug
     * @return Response
     */
    #[Route('/user/figure/{slug}/edit', name: 'edit_figure')]
    public function edit_figure(Request $request,EntityManagerInterface $manager, FigureRepository $figureRepository,string $slug = null)
    {

        $figure = $figureRepository->findOneBy(['slug'=>$slug]);

        $typeFlash = 'update';
        $type = 'update';
        $msgFlash = $this->translator->trans('editFigure.flashUpdate');

        $form = $this->createForm(
            EditFigureType::class, $figure, [
                'attr' => [
                    'type' => $type
                ]
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /**
             *
             *
             * @var User $user
             */
            $user = $this->getUser();

            /**
             * @var Figure $figureEntity
             */
            $figureEntity = $form->getData();
            $slug = $this->slugger->slug($figureEntity->getName(), '_');
            $figureEntity
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUser($user)
                ->setSlug($slug);

            if ($type === 'update') {
                $figureEntity->setUpdatedAt(new \DateTimeImmutable());
            }

            $manager->persist($figureEntity);
            $manager->flush();

            $this->addFlash($typeFlash, $msgFlash);

            return $this->redirectToRoute('home');
        }

        return $this->render(
            'figure/edit.html.twig', [
                'figure' => ($figure !== null) ? $figure : null,
                'form' => $form->createView()
            ]
        );
    }

    #[Route('/user/image/{image}/delete', name: 'delete_image')]
    public function deleteImage(ImageRepository $imageRepository,EntityManagerInterface $manager,Image $image) {

        $param = $image->getFigure()->getSlug();
        $figure = $image->getFigure();

        if ($image !== null) {

            $manager->remove($image);
            $manager->flush();
            $this->addFlash('danger', $this->translator->trans('figure.image.delete'));

            if ($image->getMain() === true) {
                $newMain = current($imageRepository->findBy(['figure'=>$figure]));
                $newMain->setMain(true);
                $manager->persist($newMain);
                $manager->flush();
            }

        } else {
            $this->addFlash('danger', $this->translator->trans('figure.image.notfound'));

        }

        return $this->redirectToRoute('edit_figure',['slug'=>$param]);

    }

    #[Route('/user/video/{video}/delete', name: 'delete_video')]
    public function deleteVideo(EntityManagerInterface $manager,Video $video) {

        $param = $video->getFigure()->getSlug();

        if ($video !== null) {
            $manager->remove($video);
            $manager->flush();
            $this->addFlash('danger', $this->translator->trans('figure.video.delete'));
        } else {
            $this->addFlash('danger', $this->translator->trans('figure.video.notfound'));

        }

        return $this->redirectToRoute('edit_figure',['slug'=>$param]);

    }

    #[Route('/user/image/{image}/main', name: 'main_image')]
    public function setMain(ImageRepository $imageRepository,EntityManagerInterface $manager,Image $image) {

        $param = $image->getFigure()->getSlug();

        $images = $imageRepository->findBy(['figure'=>$image->getFigure()->getId()]);

        if ($image !== null) {

            if (!empty($images)) {
                /** @var Image $object */
                foreach ($images as $object) {
                    $object->setMain(false);
                    $manager->persist($object);
                    $manager->flush();
                }
            }

            $image->setMain(true);
            $manager->persist($image);
            $manager->flush();
            $this->addFlash('update', $this->translator->trans('figure.image.main.success'));
        } else {
            $this->addFlash('danger', $this->translator->trans('figure.image.notfound'));

        }

        return $this->redirectToRoute('edit_figure',['slug'=>$param]);

    }

}
