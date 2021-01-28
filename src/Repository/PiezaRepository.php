<?php

namespace App\Repository;

use App\Entity\Pieza;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pieza|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pieza|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pieza[]    findAll()
 * @method Pieza[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PiezaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pieza::class);
    }

    // /**
    //  * @return Pieza[] Returns an array of Pieza objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Pieza
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
