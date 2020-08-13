<?php

namespace App\Repository;

use App\Entity\Articles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Articles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Articles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Articles[]    findAll()
 * @method Articles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticlesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Articles::class);
    }

    // /**
    //  * @return Articles[] Returns an array of Articles objects
    //  */

    public function checkIfContains($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.Title LIKE :val OR a.Content LIKE :val OR a.createdAt LIKE :val OR c.Content LIKE :val OR c.addedAt LIKE :val')
            ->setParameter('val', '%' . $value . '%')
            ->join("a.comments", "c")
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
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

    public function getLastId()
    {
        return $this->createQueryBuilder('a')
            ->select('a.id')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()['id'];
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
