<?php


namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class Paginator
{
    /**
     * @var array<object>
     */
    private array $results;
    private int $nbResults;
    private int $nbAllResults;
    private int $page;
    private string $route;
    /**
     * @var array<mixed>|null
     */
    private array|null $routeParameters;

    private EntityManagerInterface $manager;
    private RequestStack $request;

    public function __construct(private int $nbDisplay,private int $nbAdd,EntityManagerInterface $manager,RequestStack $request)
    {
        $this->manager = $manager;
        $this->request = $request;
    }

    /**
     * @param string $class
     * @param array<mixed> $searchCriteria
     * @param array<string> $orderBy
     * @param string $route
     * @param array<mixed>|null $routeParameters
     * @param int|null $nbParPage
     */
    public function createPaginator(
        string $class,
        array $searchCriteria,
        array $orderBy,
        string $route,
        array $routeParameters = null,
        int $nbParPage = null
    ): self {

        /** @var Request $request */
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

        return $this;

    }

    /**
     * @return object[]
     */
    public function getResults(): array {
        return $this->results;
    }

    public function getPage(): int {
        return $this->page;
    }

    public function getNbResults(): int {
        return $this->nbResults;
    }

    public function getNbAllResults(): int {
        return $this->nbAllResults;
    }

    public function getRoute(): string {
        return $this->route;
    }

    /**
     * @return array<mixed>|null
     */
    public function getRouteParameters(): array|null {
        return $this->routeParameters;
    }
}
