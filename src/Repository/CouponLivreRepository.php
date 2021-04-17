<?php

namespace App\Repository;

use App\Entity\CouponLivre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CouponLivre|null find($id, $lockMode = null, $lockVersion = null)
 * @method CouponLivre|null findOneBy(array $criteria, array $orderBy = null)
 * @method CouponLivre[]    findAll()
 * @method CouponLivre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CouponLivreRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CouponLivre::class);
    }

// SELECT user.nom, user.prenom, user.id
// FROM all_coupons
// LEFT OUTER JOIN user ON user_id = user.id where coupon_id = 24

    /**
     * @return array
     */
    // public function coupon(): array
    // {
    //     return $this->createQueryBuilder('c')
    //         ->join('c.user', 'u')
    //         ->select('u.nom, u.prenom, u.id')
    //         ->andWhere('u.userid = c.livre')
    //         ->groupBy('c.livre')
    //         ->orderBy('rate', 'DESC')
    //         ->setMaxResults(5)
    //         ->getQuery()->getResult()
    //         ;
    // }
    
    
    /*
    public function findOneBySomeField($value): ?CouponLivre
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
