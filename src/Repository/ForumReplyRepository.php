<?php

namespace App\Repository;

use App\Entity\ForumReply;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ForumReply|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumReply|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumReply[]    findAll()
 * @method ForumReply[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumReplyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ForumReply::class);
    }

    public function findAllby($id)
    {
        return $this->createQueryBuilder('f')
               ->andWhere('f.Forum = :val')
               ->setParameter('val', $id)
               ->orderBy('f.created_at', 'DESC')
               ->getQuery()
               ->getResult();
    }

    // /**
    //  * @return ForumReply[] Returns an array of ForumReply objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ForumReply
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
