<?php


namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;

class Paginator
{

    private int $nbAdd;
    private array $results;

    private string $paginator;

    public EntityManagerInterface $manager;
    private Environment $twig;

    public function __construct(private int $nbDisplay, int $nbAdd,EntityManagerInterface $manager,Environment $twig)
    {
        $this->manager = $manager;
        $this->nbAdd = $nbAdd;
        $this->twig = $twig;
    }

    public function createPaginator(
        int $page,
        string $class,
        array $searchCriteria,
        array $orderBy,
        string $route,
        array $routeParameters = null,
        int $nbParPage = null
    ): void {

        $repository = $this->manager->getRepository($class);

        $nbAdd = $this->nbAdd * $page;

        if ($nbParPage !== null) {
            $this->nbDisplay = $nbParPage;
        }

        $this->nbDisplay = $this->nbDisplay + $nbAdd;

        $page++;

        $this->results = $repository->findBy($searchCriteria,$orderBy,$this->nbDisplay);

        $nbResults = count($this->results);

        $nbAllResults = $repository->count($searchCriteria);

        $this->paginator = $this->twig->render(
            'paginator/paginator.html.twig', [
            'nbResults' => $nbResults,
            'nbAllResults' => $nbAllResults,
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
