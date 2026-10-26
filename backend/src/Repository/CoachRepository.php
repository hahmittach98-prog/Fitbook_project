<?php

namespace App\Repository;

use App\Entity\Coach;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Coach>
 */
class CoachRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Coach::class);
    }

//    /**
//     * @return Coach[] Returns an array of Coach objects
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

//    public function findOneBySomeField($value): ?Coach
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function search(?string $q, ?string $specialite, ?float $tarifMax): array
    {
        $qb = $this->createQueryBuilder('c')->join('c.user', 'u');

        if ($q) {
            $qb->andWhere('u.nom LIKE :q OR u.prenom LIKE :q OR c.description LIKE :q')
               ->setParameter('q', '%' . $q . '%');
        }
        if ($specialite) {
            $qb->andWhere('c.specialite = :sp')->setParameter('sp', $specialite);
        }
        if ($tarifMax) {
            $qb->andWhere('c.tarifHoraire <= :tarif')->setParameter('tarif', $tarifMax);
        }

        return $qb->orderBy('u.nom', 'ASC')->getQuery()->getResult();
    }

}
