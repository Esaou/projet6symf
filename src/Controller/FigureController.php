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
use App\Service\FileUpload;
use App\Service\Paginator;
use App\Service\SlugUnicity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class FigureController extends AbstractController
{

    private TranslatorInterface $translator;

    private SluggerInterface $slugger;

    public function __construct(TranslatorInterface $translator, SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
        $this->translator = $translator;
    }

    #[Route('/figure/{slug}', name: 'figure')]
    public function show(string $slug,Request $request,EntityManagerInterface $manager,Paginator $paginator): Response
    {

        $figureRepository = $manager->getRepository(Figure::class);

        /** @var Figure $figure */
        $figure = $figureRepository->findOneBy(['slug'=>$slug]);

        $form = $this->createForm(MessageType::class,null,[
            'attr' => [
                'action' => $this->generateUrl('figure',['slug'=>$slug,'_fragment'=>'messages'])
            ]
        ]);

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
            'paginator' => $paginator->createPaginator(Message::class, ['figure'=>$figure], ['createdAt'=>'desc'],'figure', ['slug' => $slug],10),
            'formMessage' => $form->createView()
            ]
        );
    }


    /**
     * @param EntityManagerInterface $manager
     * @param Figure|null $figure
     * @return RedirectResponse
     */
    #[Route('/user/{figure}/delete', name: 'delete_figure')]
    public function deleteFigure(EntityManagerInterface $manager,Figure $figure = null): RedirectResponse
    {

        $figureRepository = $manager->getRepository(Figure::class);

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
     * @param FileUpload $fileUpload
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return RedirectResponse|Response
     */
    #[Route('/user/figure/add', name: 'add_figure')]
    public function addFigure(FileUpload $fileUpload,Request $request,EntityManagerInterface $manager)
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

            $slug = $this->slugger->slug($figureEntity->getName());

            $figureEntity
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUser($user)
                ->setSlug($slug);

            $manager->persist($figureEntity);

            if (!empty($form->get('images')->getData())) {
                foreach ($form->get('images')->getData() as $image) {

                    $path = $fileUpload->upload($image,'figures');

                    if (is_iterable($path)) {
                        foreach ($path as $error) {
                            $this->addFlash('danger',$error);
                        }
                        return $this->redirectToRoute('add_figure');
                    }

                    $image = new Image();
                    $image
                        ->setFilename($path)
                        ->setMain(false)
                        ->setFigure($figureEntity);

                    $manager->persist($image);
                }
            }

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
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param FileUpload $fileUpload
     * @param string|null $slug
     * @return Response
     */
    #[Route('/user/figure/{slug}/edit', name: 'edit_figure')]
    public function editFigure(Request $request,EntityManagerInterface $manager, FileUpload $fileUpload, string $slug = null): Response
    {

        $figureRepository = $manager->getRepository(Figure::class);

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
            $slug = $this->slugger->slug($figureEntity->getName());
            $figureEntity
                ->setUser($user)
                ->setSlug($slug)
                ->setUpdatedAt(new \DateTimeImmutable());


            foreach ($form->get('images')->getData() as $image) {

                $path = $fileUpload->upload($image,'figures');

                $image = new Image();
                $image
                    ->setFilename($path)
                    ->setMain(false)
                    ->setFigure($figureEntity);

                $manager->persist($image);
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
    public function deleteImage(EntityManagerInterface $manager,Image $image): RedirectResponse
    {

        $imageRepository = $manager->getRepository(Image::class);

        $param = $image->getFigure()->getSlug();
        $figure = $image->getFigure();

        if ($image !== null) {

            $manager->remove($image);
            $this->addFlash('danger', $this->translator->trans('figure.image.delete'));

            if ($image->getMain() === true) {
                $newMain = current($imageRepository->findBy(['figure'=>$figure]));
                $newMain->setMain(true);
                $manager->persist($newMain);
            }

            $manager->flush();

        } else {
            $this->addFlash('danger', $this->translator->trans('figure.image.notfound'));

        }

        return $this->redirectToRoute('edit_figure',['slug'=>$param]);

    }

    #[Route('/user/video/{video}/delete', name: 'delete_video')]
    public function deleteVideo(EntityManagerInterface $manager,Video $video): RedirectResponse
    {

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
    public function setMain(EntityManagerInterface $manager,Image $image): RedirectResponse
    {
        $imageRepository = $manager->getRepository(Image::class);

        $param = $image->getFigure()->getSlug();

        $images = $imageRepository->findBy(['figure'=>$image->getFigure()->getId()]);

        if ($image !== null) {

            if (!empty($images)) {
                /** @var Image $object */
                foreach ($images as $object) {
                    $object->setMain(false);
                    $manager->persist($object);
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
