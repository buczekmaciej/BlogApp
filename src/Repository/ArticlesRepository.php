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

    public function adminDash()
    {
        $firstPost = $this->createQueryBuilder('a')
            ->select('a.createdAt')
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()[0]['createdAt'];

        $lastPost = $this->createQueryBuilder('a')
            ->select('a.createdAt')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()[0]['createdAt'];

        $mostLiked = $this->createQueryBuilder('a')
            ->select('COUNT(u) AS HIDDEN likes', 'a.id', 'a.Title', 'a.link', 'u.id AS uid', 'u.Username', 'a.createdAt')
            ->andWhere('a.likes IS NOT EMPTY')
            ->leftJoin('a.likes', 'u')
            ->orderBy('likes', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()[0];

        $likes = $this->createQueryBuilder('a')
            ->select('COUNT(u) AS likes')
            ->andWhere('a.likes IS NOT EMPTY')
            ->leftJoin('a.likes', 'u')
            ->getQuery()
            ->getResult()[0]['likes'];

        $mostCommented = $this->createQueryBuilder('a')
            ->select('COUNT(c) AS HIDDEN comments', 'a.id', 'a.Title', 'a.link', 'u.id AS uid', 'u.Username', 'a.createdAt')
            ->andWhere('a.comments IS NOT EMPTY')
            ->leftJoin('a.comments', 'c')
            ->leftJoin('c.User', 'u')
            ->orderBy('comments', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()[0];

        $comments = $this->createQueryBuilder('a')
            ->select('COUNT(c) AS comments')
            ->andWhere('a.comments IS NOT EMPTY')
            ->leftJoin('a.comments', 'c')
            ->getQuery()
            ->getResult()[0]['comments'];

        return ['oldest' => $firstPost, 'youngest' => $lastPost, 'liked' => $mostLiked, 'likes' => (int) $likes, 'commented' => $mostCommented, 'comments' => (int) $comments];
    }

    public function filterData($by, $way)
    {
        $qb = $this->createQueryBuilder('a');
        if ($by == 'id') {
            $qb->orderBy('a.id', $way);
        }
        if ($by == 'createdAt') {
            $qb->orderBy('a.createdAt', $way);
        }
        if ($by == 'likes') {
            $qb->select('COUNT(l) as HIDDEN likes, a')
                ->leftJoin('a.likes', 'l')
                ->orderBy('likes', $way)
                ->groupBy('a');
        }
        if ($by == 'comments') {
            $qb->select('COUNT(c) as HIDDEN comments, a')
                ->leftJoin('a.comments', 'c')
                ->orderBy('comments', $way)
                ->groupBy('a');
        }

        return $qb->getQuery()->getResult();
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
