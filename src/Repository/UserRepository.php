<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function checkMatch(string $name, string $mail)
    {
        return $this->createQueryBuilder('u')
            ->select('u.Username, u.Email, u.Password')
            ->andWhere('u.Username = :name OR u.Email = :mail')
            ->setParameter(':name', $name)
            ->setParameter(':mail', $mail)
            ->getQuery()
            ->getResult();
    }

    public function getNoUsers()
    {
        return intval($this->createQueryBuilder('u')
            ->select('COUNT(u) as Users')
            ->getQuery()
            ->getResult()[0]["Users"]);
    }

    public function filterData($by, $way)
    {
        $qb = $this->createQueryBuilder('u');
        if ($by == 'id') {
            $qb->orderBy('u.id', $way);
        }
        if ($by == 'Username') {
            $qb->orderBy('u.Username', $way);
        }
        if ($by == 'Email') {
            $qb->orderBy('u.Email', $way);
        }
        if ($by == 'Articles') {
            $qb->select('COUNT(a) as HIDDEN articles, u')
                ->leftJoin('u.Article', 'a')
                ->orderBy('articles', $way)
                ->groupBy('u');
        }

        return $qb->getQuery()->getResult();
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
