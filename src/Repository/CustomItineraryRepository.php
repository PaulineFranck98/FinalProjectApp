<?php

namespace App\Repository;

use App\Entity\CustomItinerary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CustomItinerary>
 *
 * @method CustomItinerary|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomItinerary|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomItinerary[]    findAll()
 * @method CustomItinerary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomItineraryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomItinerary::class);
    }

    //    /**
    //     * @return CustomItinerary[] Returns an array of CustomItinerary objects
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

    //    public function findOneBySomeField($value): ?CustomItinerary
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}