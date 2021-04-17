<?php

namespace App\Repository;

use App\Entity\Bandeau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Bandeau|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bandeau|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bandeau[]    findAll()
 * @method Bandeau[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BandeauRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bandeau::class);
    }

    public function findByCle($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.cle = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Bandeau[] Returns an array of Bandeau objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Bandeau
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
