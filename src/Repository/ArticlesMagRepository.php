<?php

namespace App\Repository;

use App\Entity\ArticlesMag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ArticlesMag|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticlesMag|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticlesMag[]    findAll()
 * @method ArticlesMag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticlesMagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticlesMag::class);
    }



    // /**
    //  * @return ArticlesMag[] Returns an array of ArticlesMag objects
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
    public function findOneBySomeField($value): ?ArticlesMag
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
