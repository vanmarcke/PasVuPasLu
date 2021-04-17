<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;



class CommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function countComments(): string
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function find9MoreActiveLectorCommentator(): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.user', 'u')
            ->select('count(c.user) nbrComment, u.id')
            ->andWhere('u.id = c.user')
            ->andwhere('u.roles LIKE :roles')
            ->setParameter('roles', '%"ROLE_LECTEUR"%')
            ->groupBy('c.user')
            ->orderBy('nbrComment', 'DESC')
            ->setMaxResults(9)
            ->getQuery()->getResult()
            ;
    }

    public function findCommentsValide(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.rapport IS null')
            ->orderBy('c.createAt', 'DESC')
            ->getQuery()->getResult()
            ;
    }

    public function findCommentsRapport(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.rapport = 1')
            ->orderBy('c.createAt', 'DESC')
            ->getQuery()->getResult()
            ;
    }


    /**
     * @param int|null $id
     * @return string
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findAllGreaterThanRate(?int $id): string
    {
        return $this->createQueryBuilder('c')
            ->select('AVG(c.rate)')
            ->andWhere('c.livre = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    /**
     * @param int|null $id
     * @return string
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findAllGreaterThanCoverRate(?int $id)
    {
        return $this->createQueryBuilder('c')
            ->select('AVG(c.coverRate)')
            ->andWhere('c.livre = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }
    
    /**
     * @return array
     */
    public function lastRate(): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.livre', 'l')
            ->select('AVG(c.rate) rate, l.id')
            ->andWhere('l.id = c.livre')
            ->groupBy('c.livre')
            ->orderBy('rate', 'DESC')
            ->setMaxResults(10)
            ->getQuery()->getResult()
            ;
    }

    /**
     * @return array
     */
    public function livreRate(): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.livre', 'l')
            ->select('AVG(c.rate) rate, l.id')
            ->andWhere('l.id = c.livre')
            ->groupBy('c.livre')
            ->orderBy('rate', 'DESC')
            ->setMaxResults(5)
            ->getQuery()->getResult()
            ;
    }

    /**
     * @return array
     */
    public function firstsCoverRate(): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.livre', 'l')
            ->select('AVG(c.coverRate) coverRate, l.id, l.titre, l.photo')
            ->andWhere('l.id = c.livre')
            ->groupBy('c.livre')
            ->orderBy('coverRate', 'DESC')
            ->setMaxResults(3)
            ->getQuery()->getResult()
            ;
    }
}
