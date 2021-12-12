<?php


namespace App\Service;

use Twig\Environment;

class Paginator
{
    private Environment $twig;
    public int $nbDisplay;
    public string $paginator;
    public array $results;

    public function __construct(Environment $twig,int $nbDisplay = 10,string $paginator = '')
    {
        $this->twig = $twig;
        $this->nbDisplay = $nbDisplay;
        $this->paginator = $paginator;
    }

    public function paginate(
        $repository,
        int $nbEntities,
        string $route,
        int $nbAddResults,
        array $searchCriteria = null,
        array $orderBy = null,
        array $routeParameters = null
    ): void {

        $this->nbDisplay = $nbEntities;

        if ($searchCriteria === null) {
            $allResults = $repository->findAll();
            $nbAllResults = count($allResults);
            $this->results = $repository->findBy([], $orderBy, $this->nbDisplay);
        } else {
            $allResults = $repository->findBy($searchCriteria);
            $nbAllResults = count($allResults);
            $this->results = $repository->findBy($searchCriteria, $orderBy, $this->nbDisplay);
        }

        $nbResults = count($this->results);

        $this->paginator = $this->twig->render(
            'paginator/paginator.html.twig', [
            'nbResults' => $nbResults,
            'nbAllResults' => $nbAllResults,
            'route' => $route,
            'nbAddResults' => $nbAddResults,
            'routeParameters' => ($routeParameters !== null) ? $routeParameters : null
            ]
        );

    }

    public function getResults(): array
    {
        return $this->results;
    }

    public function getPaginator(): string
    {
        return  $this->paginator;
    }
}
