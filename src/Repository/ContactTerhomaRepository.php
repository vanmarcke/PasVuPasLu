<?php

namespace App\Repository;

use App\Entity\ContactTerhoma;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ContactTerhoma|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactTerhoma|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactTerhoma[]    findAll()
 * @method ContactTerhoma[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactTerhomaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactTerhoma::class);
    }

    // /**
    //  * @return ContactTerhoma[] Returns an array of ContactTerhoma objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ContactTerhoma
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
