<?php

namespace App\Repository;

use App\Entity\Edito;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Edito|null find($id, $lockMode = null, $lockVersion = null)
 * @method Edito|null findOneBy(array $criteria, array $orderBy = null)
 * @method Edito[]    findAll()
 * @method Edito[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EditoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Edito::class);
    }

    // /**
    //  * @return Edito[] Returns an array of Edito objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Edito
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
