<?php


namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;

class Paginator
{

    public int $nbDisplay;
    public int $nbAdd;
    public array $results;

    private string $paginator;

    public EntityManagerInterface $manager;
    private Environment $twig;

    public function __construct(int $nbDisplay,int $nbAdd,EntityManagerInterface $manager,Environment $twig,string $paginator = '')
    {
        $this->manager = $manager;
        $this->nbDisplay = $nbDisplay;
        $this->nbAdd = $nbAdd;
        $this->twig = $twig;
        $this->paginator = $paginator;
    }

    public function createPaginator(
        int $page,
        object $class,
        array $searchCriteria,
        array $orderBy,
        string $route,
        array $routeParameters = null
    ): void {

        //TODO PAGINATOR

        $repository = $this->manager->getRepository($class::class);

        $nbAdd = $this->nbAdd * $page;

        $this->nbDisplay = $this->nbDisplay + $nbAdd;

        $page++;

        $this->results = $repository->findBy($searchCriteria,$orderBy,$this->nbDisplay);

        $nbResults = count($repository->findBy($searchCriteria,$orderBy,$this->nbDisplay));

        $nbAllResults = $repository->count($searchCriteria);

        $this->paginator = $this->twig->render(
            'paginator/paginator.html.twig', [
            'nbResults' => $nbResults,
            'nbAllResults' => $nbAllResults,
            'nbAdd' => $nbAdd,
            'route' => $route,
            'page' => $page,
            'routeParameters' => ($routeParameters !== null) ? $routeParameters : null
            ]
        );

    }

    public function getPagination() {
        return $this->paginator;
    }

    public function getEntities() {
        return $this->results;
    }

}
