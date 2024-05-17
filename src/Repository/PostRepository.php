<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }


    public function findPostsByPlace($placeId)
    {
        return $this->createQueryBuilder('p')
            ->where('p.place = :placeId')
            ->setParameter('placeId', $placeId)
            ->getQuery()
            ->getResult();
    }

    public function findPostsByCity($cityId)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.place', 'pl')  // Joindre les places aux posts
            ->innerJoin('pl.city', 'c')   // Joindre les villes aux places
            ->where('c.id = :cityId')     // Filtrer les places en fonction de l'ID de la ville
            ->setParameter('cityId', $cityId)
            ->getQuery()
            ->getResult();
    }
    //    /**
    //     * @return Post[] Returns an array of Post objects
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

    //    public function findOneBySomeField($value): ?Post
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
