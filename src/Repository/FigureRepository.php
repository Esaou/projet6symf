<?php

namespace App\Repository;

use App\Entity\Figure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @method Figure|null find($id, $lockMode = null, $lockVersion = null)
 * @method Figure|null findOneBy(array $criteria, array $orderBy = null)
 * @method Figure[]    findAll()
 * @method Figure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FigureRepository extends ServiceEntityRepository
{

    private SluggerInterface $slugger;

    public function __construct(ManagerRegistry $registry, SluggerInterface $slugger)
    {
        parent::__construct($registry, Figure::class);
        $this->slugger = $slugger;
    }

    public function getFigureBySlug(Figure $figure)
    {
        $slug = $this->slugger->slug($figure->getName());

        $query = $this->createQueryBuilder('figure')
         ->select('count(figure.id)')
         ->where('figure.slug in (:slug)')
         ->setParameter('slug', $slug);

        if (null !== $figure->getId()) {
         $query = $query
             ->andWhere('figure.id != :id')
             ->setParameter('id', $figure->getId());
        }

        $count = $query
            ->getQuery()
            ->getSingleScalarResult();

        return $count;
    }
}