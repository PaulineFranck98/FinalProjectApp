<?php

namespace App\Repository;

use App\Entity\Place;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Place>
 *
 * @method Place|null find($id, $lockMode = null, $lockVersion = null)
 * @method Place|null findOneBy(array $criteria, array $orderBy = null)
 * @method Place[]    findAll()
 * @method Place[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Place::class);
    }

    public function findByThemeAndCompanion($themeId, $companionId)
    {
        return $this->createQueryBuilder('p')
            ->join('p.themes', 't')
            ->join('p.companions', 'c')
            ->where('t.id = :themeId')
            ->andWhere('c.id = :companionId')
            ->setParameter('themeId', $themeId)
            ->setParameter('companionId', $companionId)
            ->getQuery()
            ->getResult()
        ;
    }
    

    public function getAverageRating($placeId)
    {
        return $this->createQueryBuilder('p')
            ->select('AVG(r.rating) as averageRating')
            ->join('p.ratings', 'r')
            ->where('p.id = :placeId')
            ->setParameter('placeId', $placeId)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }


    public function countPlacesByCityId($cityId)
    {
        return $this->createQueryBuilder('p')
                    ->select('COUNT(p.id)')
                    ->innerJoin('p.city' , 'c')
                    ->where('c.id = :cityId')
                    ->setParameter('cityId', $cityId)
                    ->getQuery()
                    ->getSingleScalarResult();
    }

    // public function findCitiesWithPlaces()
    // {
    //     return $this->createQueryBuilder('p')
    //     ->select('p.city')
    //     ->getQuery()
    //     ->getResult()
    //     ;
    // }
    //    /**
    //     * @return Place[] Returns an array of Place objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Place
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
