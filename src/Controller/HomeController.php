<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Service\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    #[Route('/home', name: 'home', methods: 'GET')]
    public function home(Request $request,Paginator $paginator): Response
    {

        $page = 0;

        if ($request->query->get('page')) {
            $page = (int)$request->query->get('page');
        }

        $paginator->createPaginator($page,Figure::class,[],[], 'home');

        return $this->render(
            'home/home.html.twig', [
                'figures' => $paginator->getEntities(),
                'paginator' => $paginator->getPagination()
            ]
        );
    }
}
