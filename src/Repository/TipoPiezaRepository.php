<?php

namespace App\Repository;

use App\Entity\TipoPieza;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TipoPieza|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoPieza|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoPieza[]    findAll()
 * @method TipoPieza[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoPiezaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoPieza::class);
    }

    // /**
    //  * @return TipoPieza[] Returns an array of TipoPieza objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TipoPieza
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
