<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Repository\FigureRepository;
use App\Service\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    #[Route('/home', name: 'home')]
    public function home(Request $request,Paginator $paginator): Response
    {

        $page = 0;

        if ($request->request->get('page')) {
            $page = (int)$request->request->get('page');
        }

        dd((int)$request->request->get('page'));

        $paginator->createPaginator($page,new Figure(),[],[], 'home');

        return $this->render(
            'home/home.html.twig', [
                'figures' => $paginator->getEntities(),
                'paginator' => $paginator->getPagination()
            ]
        );
    }
}
