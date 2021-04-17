<?php

namespace App\Repository;

use App\Entity\ProfilUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProfilUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfilUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfilUser[]    findAll()
 * @method ProfilUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfilUserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProfilUser::class);
    }

    // /**
    //  * @return ProfilUser[] Returns an array of ProfilUser objects
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
    public function findOneBySomeField($value): ?ProfilUser
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
