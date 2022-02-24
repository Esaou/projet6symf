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

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Figure::class);
    }

    public function getFigureBySlug(Figure $figure)
    {
         $query = $this->createQueryBuilder('figure')
            ->where('figure.slug in (:slug)')
            ->setParameter('slug', $figure->getSlug());

         if (null !== $figure->getId()) {
             $query = $query
                 ->andWhere('figure.id in (:id)')
                 ->setParameter('id', $figure->getId());
         }

         $query = $query
             ->getQuery()
             ->getSingleResult();

         return $query;
    }
}