<?php

namespace App\Repository;

use App\Entity\Seance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Seance>
 */
class SeanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Seance::class);
    }

//    /**
//     * @return Seance[] Returns an array of Seance objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Seance
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function search(?string $date, ?int $salleId, ?int $coachId, ?int $coursId, ?float $prixMax): array
    {
        $qb = $this->createQueryBuilder('s');

        if ($date) {
            $qb->andWhere('s.dateSeance = :d')
               ->setParameter('d', new \DateTimeImmutable($date), 'date_immutable');
        }
        if ($salleId) { $qb->andWhere('s.salle = :salle')->setParameter('salle', $salleId); }
        if ($coachId) { $qb->andWhere('s.coach = :coach')->setParameter('coach', $coachId); }
        if ($coursId) { $qb->andWhere('s.cours = :cours')->setParameter('cours', $coursId); }
        if ($prixMax !== null) { $qb->andWhere('s.prix <= :prix')->setParameter('prix', $prixMax); }

        return $qb->orderBy('s.dateSeance', 'ASC')
                  ->addOrderBy('s.heureDebut', 'ASC')
                  ->getQuery()->getResult();
    }
}
