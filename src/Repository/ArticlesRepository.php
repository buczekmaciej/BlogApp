<?php

namespace App\Repository;

use App\Entity\Articles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Articles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Articles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Articles[]    findAll()
 * @method Articles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticlesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Articles::class);
    }

    // /**
    //  * @return Articles[] Returns an array of Articles objects
    //  */
    
    public function checkIfContain($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.Title LIKE :val OR a.Content LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function mostLiked()
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(u) AS HIDDEN likes', 'a')
            ->andWhere('a.likes IS NOT EMPTY')
            ->leftJoin('a.likes', 'u')
            ->orderBy('likes', 'DESC')
            ->groupBy('a')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Articles
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
