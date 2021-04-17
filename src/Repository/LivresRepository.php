<?php

namespace App\Repository;

use App\Entity\Livres;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Livres|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livres|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livres[]    findAll()
 * @method Livres[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivresRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Livres::class);
    }

    // SELECT * FROM `user` WHERE email Like '%lo%' LIMIT 5
    public function search($search, $max = 5)
    {
        $qb = $this->createQueryBuilder('l')
            ->where('l.titre LIKE :search')
            ->setParameter('search', '%'.$search.'%')
            ->setMaxResults($max)
            ->orderBy('l.CreatedAt', 'DESC');
        return $qb->getQuery()->getResult();
    }

    /**
     * List all object with paginator.
     */
    public function paginator(int $page, int $maxResults): Paginator
    {
        $qb = $this->createQueryBuilder('p');

        $qb
            ->setFirstResult(($page - 1) * $maxResults)
            ->setMaxResults($maxResults)
            ->orderBy('p.createAt', 'DESC');

        return new Paginator($qb);
    }

    public function ListBooks($maxResults)
    {
        $qb = $this->createQueryBuilder('b')
            ->setMaxResults($maxResults)
            ->orderBy('b.CreatedAt', 'DESC');
        return $qb->getQuery()->getResult();
    }


    public function getArticlesWithComment(): array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.comments', 'c')
            ->addSelect('c')
            ->orderBy('a.createAt', 'DESC')
            ->getQuery()->getResult();
    }

    public function countLivres(): string
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function saveNewArticle(Livres $article): void
    {
        $this->_em->persist($article);
        $this->_em->flush();
    }

    public function saveExistingArticle(): void
    {
        try {
            $this->_em->flush();
        } catch (OptimisticLockException $e) {
        } catch (ORMException $e) {
        }
    }

    // /**
    //  * @return Livres[] Returns an array of Livres objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Livres
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
