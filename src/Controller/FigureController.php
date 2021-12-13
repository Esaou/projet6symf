<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\FigureRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use App\Service\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class FigureController extends AbstractController
{

    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    #[Route('/figure/{slug}/{nbEntities}', name: 'figure')]
    public function show(string $slug,UserRepository $userRepository,Request $request,EntityManagerInterface $manager,FigureRepository $figureRepository,Paginator $paginator,MessageRepository $messageRepository,int $nbEntities = 2): Response
    {

        $figure = $figureRepository->findOneBy(['slug'=>$slug]);

        $paginator->paginate($messageRepository, $nbEntities, "figure", 2, ['figure'=>$figure], ['createdAt'=>'desc'], ['slug' => $slug]);

        $form = $this->createForm(MessageType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /**
 * @var Message $messageEntity 
*/
            $messageEntity = $form->getData();
            $messageEntity->setCreatedAt(new \DateTimeImmutable());
            $messageEntity->setUser($messageEntity->getUser());
            $messageEntity->setFigure($figure);

            $manager->persist($messageEntity);
            $manager->flush();

            $this->addFlash('success', $this->translator->trans('showFigure.confirmPost'));
        }

        return $this->render(
            'figure/show.html.twig', [
            'figure' => $figure,
            'messages' => $paginator->getResults(),
            'paginator' => $paginator->getPaginator(),
            'formMessage' => $form->createView()
            ]
        );
    }

    /**
     * @param Figure $figure
     * @param EntityManagerInterface $manager
     * @param FigureRepository $figureRepository
     */
    #[Route('/{figure}/delete', name: 'delete_figure')]
    public function deleteFigure(EntityManagerInterface $manager, FigureRepository $figureRepository,Figure $figure = null) {
        $figure = $figureRepository->findOneBy(['id'=>$figure]);

        if ($figure !== null) {
            $manager->remove($figure);
            $manager->flush();
            $this->addFlash('success',$this->translator->trans('figure.delete'));
        } else {
            $this->addFlash('danger',$this->translator->trans('figure.notfound'));
        }

        return $this->redirectToRoute('home');

    }
}
