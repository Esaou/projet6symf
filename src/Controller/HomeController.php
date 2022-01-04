<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Service\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    #[Route('/home', name: 'home', methods: 'GET')]
    public function home(Paginator $paginator): Response
    {

        return $this->render(
            'home/home.html.twig', [
                'paginator' => $paginator->createPaginator(Figure::class,[],['createdAt'=>'desc'], 'home')
            ]
        );
    }
}
