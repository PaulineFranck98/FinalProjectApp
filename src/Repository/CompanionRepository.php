<?php

namespace App\Repository;

use App\Entity\Companion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Companion>
 *
 * @method Companion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Companion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Companion[]    findAll()
 * @method Companion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Companion::class);
    }

    //    /**
    //     * @return Companion[] Returns an array of Companion objects
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

    //    public function findOneBySomeField($value): ?Companion
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
