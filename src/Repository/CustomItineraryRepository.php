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
            // les villes intermédiaires
            ->join('ci.cities', 'cic') 
            ->where('ci.user = :userId')
            // je vérifie si la ville est dans dans departure, arrival, ou la collection citites
            ->andWhere(':cityId = ci.departure OR :cityId = ci.arrival OR cic.id = :cityId') // Vérifier si la ville est dans les départ, arrivée ou villes intermédiaires
            ->setParameter('userId', $userId)
            ->setParameter('cityId', $cityId)
            ->getQuery()
            ->getSingleScalarResult()
            ;
            
       }





       public function findItinerariesByPlaceAndUser($placeId, $userId)
        {
            $entityManager = $this->getEntityManager();
            $cityId = $entityManager->getRepository(Place::class)->find($placeId)->getCity()->getId();

            return $this->createQueryBuilder('ci')
                ->select('ci')
                ->leftJoin('ci.cities', 'cic')
                // ->join('cic.id', 'c')
                ->join('cic.places', 'p')
                ->where('ci.user = :userId')
                ->andWhere('p.id != :placeId OR cic.id IS NULL')
                ->setParameter('userId', $userId)
                ->setParameter('placeId', $placeId)
                ->getQuery()
                ->getResult();
        }

    }