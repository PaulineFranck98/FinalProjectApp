<?php

namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<City>
 *
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

    // public function findCitiesWithPlaces()
    // {
    //     return $this->createQueryBuilder('c')
    //     ->select('c.cityName')
    //     ->getQuery()
    //     ->getResult()
    //     ;
    // }

    // public function findCitiesWithPlaces($cityName)
    // {
    //     return $this->createQueryBuilder('c')
    //     ->select('c.cityName')
    //     ->where('c.cityName like :cityName')
    //     ->setParameter('cityName', '%' . $cityName. '%')
    //     ->getQuery()
    //     ->getResult()
    //     ;
    // }


    public function findPlacesByCityId($cityId, $themeFilters = null, $companionFilters = null)
    {
        $query = $this->createQueryBuilder('c')
            ->innerJoin('c.places', 'p')
            ->select('c, p')
            ->where('c.id = :cityId')
            ->setParameter('cityId', $cityId);

        // Je filtre les données
        if($themeFilters != null){
            $query->innerJoin('p.themes', 't')
                ->andWhere('t.id IN (:themes)')
                ->setParameter('themes', array_values($themeFilters));
        }
        if ($companionFilters != null) {
            $query->innerJoin('p.companions', 'comp')
                  ->andWhere('comp.id IN (:companions)')
                  ->setParameter('companions', array_values($companionFilters));
        }

        

        return $query->getQuery()->getResult();
    }


    public function findCitiesWithPlaces()
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.places', 'p')
            ->select('c.cityName')
            ->groupBy('c.cityName')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return City[] Returns an array of City objects
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

    //    public function findOneBySomeField($value): ?City
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
