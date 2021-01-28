<?php

namespace App\Repository;

use App\Entity\Day;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Day|null find($id, $lockMode = null, $lockVersion = null)
 * @method Day|null findOneBy(array $criteria, array $orderBy = null)
 * @method Day[]    findAll()
 * @method Day[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Day::class);
    }


    public function isOpenADay(){
        return count($this->createQueryBuilder('w')
                ->andWhere('w.open = :val')
                ->setParameter('val', true)
                ->getQuery()
                ->getResult())>0;
    }

    public function getOpenDay(){
        return $this->createQueryBuilder('w')
            ->andWhere('w.open = :val')
            ->setParameter('val', true)
            ->orderBy('w.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    public function findByFecha($value): ?Day
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.fecha = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Day[] Returns an array of Day objects
    //  */
    
    public function findByDateInterval($initDate,$endDate)
    {
        return $this->createQueryBuilder('d')
            ->where('d.fecha >= :val')
            ->andWhere('d.fecha <= :val2')
            ->setParameter('val', $initDate)
            ->setParameter('val2', $endDate)
            ->orderBy('d.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    
    // /**
    //  * @return Day[] Returns an array of Day objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Day
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
