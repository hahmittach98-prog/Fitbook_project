<?php

namespace App\Repository;

use App\Entity\Cours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cours>
 */
class CoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cours::class);
    }

//    /**
//     * @return Cours[] Returns an array of Cours objects
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

//    public function findOneBySomeField($value): ?Cours
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function search(?string $q, ?string $niveau): array
    {
        $qb = $this->createQueryBuilder('c');

        if ($q) {
            $qb->andWhere('c.nom LIKE :q OR c.description LIKE :q')
               ->setParameter('q', '%' . $q . '%');
        }
        if ($niveau) {
            $qb->andWhere('c.niveau = :niveau')->setParameter('niveau', $niveau);
        }

        return $qb->orderBy('c.nom', 'ASC')->getQuery()->getResult();
    }
}
