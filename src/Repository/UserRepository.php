<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    const MAX_RESULTS = 5;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function countLecteurs(): string {
        $qb = $this->createQueryBuilder('A')
            ->select('COUNT(A)')
            ->where('A.roles LIKE :roles')
            ->setParameter('roles', '%"ROLE_LECTEUR"%');
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countAuteurs(): string {
        $qb = $this->createQueryBuilder('A')
            ->select('COUNT(A)')
            ->where('A.roles LIKE :roles')
            ->setParameter('roles', '%"ROLE_AUTEUR"%');
            return $qb->getQuery()->getSingleScalarResult();
    }

    public function save(User $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function saveNewPassword()
    {
        $this->_em->flush();
    }
    /**
     * Récupérer les derniers auteurs
     */
    public function findLatestAuteurs($role, $limit = 100)
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
            ->where('a.roles LIKE :roles')
            ->setParameter('roles', '%"'.$role.'"%')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * Récupérer les derniers auteurs
     */
    public function findEmailsAuteurs($email)
    {
        return $this->createQueryBuilder('u')
            
            ->select('u.email')
            ->Where('u.roles LIKE :role')
            ->setParameter('role', '%"'.'ROLE_AUTEUR'.'"%')
            ->orderBy('u.roles', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return User[] Returns an array of User objects
     */
    public function findEmailsLecteurs($email)
    {
        return $this->createQueryBuilder('u')
            
            ->select('u.email')
            ->Where('u.roles LIKE :role')
            ->setParameter('role', '%"'.'ROLE_LECTEUR'.'"%')
            ->orderBy('u.roles', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
