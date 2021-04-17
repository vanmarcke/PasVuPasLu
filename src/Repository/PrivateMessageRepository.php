<?php

namespace App\Repository;

use App\Entity\PrivateMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method PrivateMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrivateMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrivateMessage[]    findAll()
 * @method PrivateMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrivateMessageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PrivateMessage::class);
    }

    // -> Permet de récupérer les messages pour les utilisateurs connectés
    //- SELECT * FROM private_message WHERE user_sender_id = '1' OR user_receiver = '1' and is_head = true GROUP BY user_sender_id, user_receiver HAVING COUNT(*) >= 1
    public function getAllByRecipient(UserInterface $user)
    {
        $qb = $this->createQueryBuilder('pm')
            ->groupBy('pm.userSender')
            ->groupBy('pm.userReceiver')
            ->having('COUNT(pm) >= 1')
            ->andWhere('pm.userReceiver = :user OR pm.userSender = :user')
            ->andWhere('pm.is_head = true')
            ->setParameter('user', $user)
            ->orderBy('pm.created_at', 'DESC');
        return $qb->getQuery()->getResult();
    }

    // SELECT * FROM `private_message` WHERE user_receiver_id = 15 AND user_sender_id = 1 OR user_sender_id = 15 AND user_receiver_id = 1
    public function getById($id, UserInterface $user)
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.userReceiver = :id AND p.userSender = :user OR p.userSender = :id AND p.userReceiver = :user')
            ->setParameter('id', $id)
            ->setParameter('user', $user)
            ->orderBy('p.created_at', 'ASC');
        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return PrivateMessage[] Returns an array of PrivateMessage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PrivateMessage
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
