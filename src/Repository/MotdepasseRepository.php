<?php

namespace App\Repository;

use App\Entity\Motdepasse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Motdepasse>
 *
 * @method Motdepasse|null find($id, $lockMode = null, $lockVersion = null)
 * @method Motdepasse|null findOneBy(array $criteria, array $orderBy = null)
 * @method Motdepasse[]    findAll()
 * @method Motdepasse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MotdepasseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Motdepasse::class);
    }

//    /**
//     * @return Motdepasse[] Returns an array of Motdepasse objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Motdepasse
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
