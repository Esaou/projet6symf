<?php

namespace App\Controller;

use App\Repository\FigureRepository;
use App\Service\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/{nbEntities}', name: 'home')]
    public function home(FigureRepository $figureRepository,Paginator $paginator,int $nbEntities = 10): Response
    {
        $paginator->paginate($figureRepository, $nbEntities, 'home', 5);

        return $this->render(
            'home/home.html.twig', [
                'figures' => $paginator->getResults(),
                'paginator' => $paginator->getPaginator()
            ]
        );
    }
}
