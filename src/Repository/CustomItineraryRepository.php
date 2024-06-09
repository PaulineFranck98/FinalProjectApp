<?php

namespace App\Repository;

use App\Entity\Place;
use App\Entity\CustomItinerary;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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
       public function findLastPublicItineraries(): array
       {
           return $this->createQueryBuilder('ci')
               ->andWhere('ci.isPublic = :val')
               ->setParameter('val', true)
               ->orderBy('ci.creationDate', 'DESC')
               ->setMaxResults(3)
               ->getQuery()
               ->getResult()
           ;
       }

       public function countItinerariesByPlaceAndUser($placeId, $userId)
       {
        
        $entityManager = $this->getEntityManager();
         // Récupérer directement l'ID de la ville depuis l'entité Place
         $cityId = $entityManager->getRepository(Place::class)->find($placeId)->getCity()->getId();

         return $this->createQueryBuilder('ci')
            ->select('COUNT(DISTINCT ci.id) AS count')
            // Pas besoin de joindre departure ou arrival pour obtenir juste leurs IDs
            ->leftJoin('ci.cities', 'cic') // Joindre les villes intermédiaires
            ->where('ci.user = :userId') // Itinéraires appartenant à l'utilisateur
            ->andWhere(':cityId = ci.departure OR :cityId = ci.arrival OR cic.id = :cityId') // Vérifier si la ville est dans les départ, arrivée ou villes intermédiaires
            ->setParameter('userId', $userId)
            ->setParameter('cityId', $cityId)
            ->getQuery()
            ->getSingleScalarResult();
            // return $this->createQueryBuilder('ci')
            //     ->select('COUNT(DISTINCT ci.id) AS count')
            //     ->join('ci.cities', 'c')
            //     // ->join('ci.departure', 'dep') 
            //     // ->join('ci.arrival', 'arr')
            //     // ->join('cic.id', 'c')
            //     ->join('c.places', 'p')
            //     ->where('p.id = :placeId AND ci.user = :userId')
            //     ->andWhere('ci.departure = c OR ci.arrival = c OR c MEMBER OF ci.cities)')
            //     // ->setParameter('placeId', $placeId)
            //     // ->setParameter('user', $userId)
            //     // ->andWhere('cDep = c OR cArr = c OR c IN (cic.cities)')
            //     ->setParameter('placeId', $placeId)
            //     ->setParameter('userId', $userId)
            //     ->getQuery()
            //     ->getSingleScalarResult()
            ;

       }

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
