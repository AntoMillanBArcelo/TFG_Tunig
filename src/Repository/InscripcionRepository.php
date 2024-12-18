<?php

namespace App\Repository;

use App\Entity\Inscripcion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Inscripcion>
 */
class InscripcionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inscripcion::class);
    }
        
        public function findHighestPositionsByRace(): array
        {
            return $this->createQueryBuilder('i')
                ->select('circuito.ubicacion AS carrera', 'MIN(i.posicion) AS posicion_mas_alta')
                ->join('i.carrera', 'c') 
                ->join('c.circuito', 'circuito')
                ->groupBy('circuito.ubicacion')
                ->orderBy('posicion_mas_alta', 'ASC')
                ->getQuery()
                ->getResult();
        }


    

    //    /**
    //     * @return Inscripcion[] Returns an array of Inscripcion objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Inscripcion
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}