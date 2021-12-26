<?php


namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Paginator
{

    private array $results;
    private int $nbResults;
    private int $nbAllResults;
    private int $page;
    private string $route;
    private array|null $routeParameters;

    private EntityManagerInterface $manager;
    private RequestStack $request;

    public function __construct(private int $nbDisplay,private int $nbAdd,EntityManagerInterface $manager,RequestStack $request)
    {
        $this->manager = $manager;
        $this->request = $request;
    }

    public function createPaginator(
        string $class,
        array $searchCriteria,
        array $orderBy,
        string $route,
        array $routeParameters = null,
        int $nbParPage = null
    ): void {

        $request = $this->request->getCurrentRequest();

        $this->route = $route;
        $this->routeParameters = $routeParameters;
        $this->page = 0;

        if ($request->query->get('page')) {
            $this->page = (int)$request->query->get('page');
        }

        $repository = $this->manager->getRepository($class);

        $nbAdd = $this->nbAdd * $this->page;

        if ($nbParPage !== null) {
            $this->nbDisplay = $nbParPage;
        }

        $this->nbDisplay = $this->nbDisplay + $nbAdd;

        $this->page++;

        $this->results = $repository->findBy($searchCriteria,$orderBy,$this->nbDisplay);

        $this->nbResults = count($this->results);

        $this->nbAllResults = $repository->count($searchCriteria);

    }

    public function getResults() {
        return $this->results;
    }

    public function getPage() {
        return $this->page;
    }

    public function getNbResults() {
        return $this->nbResults;
    }

    public function getNbAllResults() {
        return $this->nbAllResults;
    }

    public function getRoute() {
        return $this->route;
    }

    public function getRouteParameters() {
        return $this->routeParameters;
    }
}
