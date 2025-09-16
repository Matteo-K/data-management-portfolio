<?php

namespace App\Repository;

use App\Entity\TrophyRoad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TrophyRoad>
 *
 * @method TrophyRoad|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrophyRoad|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrophyRoad[]    findAll()
 * @method TrophyRoad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrophyRoadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrophyRoad::class);
    }

//    /**
//     * @return TrophyRoad[] Returns an array of TrophyRoad objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TrophyRoad
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
