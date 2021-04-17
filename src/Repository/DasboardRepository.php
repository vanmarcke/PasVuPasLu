<?php

namespace App\Repository;

use App\Entity\Dasboard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Dasboard|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dasboard|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dasboard[]    findAll()
 * @method Dasboard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DasboardRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Dasboard::class);
    }

    // /**
    //  * @return Dasboard[] Returns an array of Dasboard objects
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
    public function findOneBySomeField($value): ?Dasboard
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
