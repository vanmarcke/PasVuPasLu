<?php

namespace App\Repository;

use App\Entity\AllCoupons;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AllCoupons|null find($id, $lockMode = null, $lockVersion = null)
 * @method AllCoupons|null findOneBy(array $criteria, array $orderBy = null)
 * @method AllCoupons[]    findAll()
 * @method AllCoupons[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AllCouponsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AllCoupons::class);
    }

    // /**
    //  * @return AllCoupons[] Returns an array of AllCoupons objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AllCoupons
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
