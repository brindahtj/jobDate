<?php

namespace App\Repository;

use App\Entity\HomeSetting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HomeSetting>
 *
 * @method HomeSetting|null find($id, $lockMode = null, $lockVersion = null)
 * @method HomeSetting|null findOneBy(array $criteria, array $orderBy = null)
 * @method HomeSetting[]    findAll()
 * @method HomeSetting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HomeSettingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HomeSetting::class);
    }

//    /**
//     * @return HomeSetting[] Returns an array of HomeSetting objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?HomeSetting
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
