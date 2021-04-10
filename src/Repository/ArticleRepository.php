<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function getArticlesContainingString(string $query)
    {
        $articlesContaining = $this->createQueryBuilder('a')
            ->andWhere('a.title LIKE :query OR a.content LIKE :query')
            ->setParameter(":query", "%$query%")
            ->getQuery()
            ->getResult();

        return array_unique($articlesContaining, SORT_REGULAR);
    }

    public function checkExistance(string $content, string $title)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.title = :title OR a.content = :content')
            ->setParameters([':title' => $title, ':content' => $content])
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
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
public function findOneBySomeField($value): ?Article
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
