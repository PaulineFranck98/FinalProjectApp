<?php

namespace App\Repository;

use App\Entity\Commentary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commentary>
 *
 * @method Commentary|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commentary|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commentary[]    findAll()
 * @method Commentary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentaryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentary::class);
    }

    // public function findCommentsByPostId($postId)
    // {
    //     return $this->createQueryBuilder('p')
    //         ->where('p.post = :postId')
    //         ->setParameter('postId', $postId)
    //         ->getQuery()
    //         ->getResult();
    // }

    //    /**
    //     * @return Commentary[] Returns an array of Commentary objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Commentary
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
