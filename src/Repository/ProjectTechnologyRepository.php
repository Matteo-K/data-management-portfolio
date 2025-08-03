<?php

namespace App\Repository;

use App\Entity\ProjectTechnology;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjectTechnology>
 *
 * @method ProjectTechnology|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectTechnology|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectTechnology[]    findAll()
 * @method ProjectTechnology[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectTechnologyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectTechnology::class);
    }

//    /**
//     * @return ProjectTechnology[] Returns an array of ProjectTechnology objects
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

//    public function findOneBySomeField($value): ?ProjectTechnology
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
