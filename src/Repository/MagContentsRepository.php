<?php

namespace App\Repository;

use App\Entity\MagContents;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MagContents|null find($id, $lockMode = null, $lockVersion = null)
 * @method MagContents|null findOneBy(array $criteria, array $orderBy = null)
 * @method MagContents[]    findAll()
 * @method MagContents[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MagContentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MagContents::class);
    }

    // /**
    //  * @return MagContents[] Returns an array of MagContents objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MagContents
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
