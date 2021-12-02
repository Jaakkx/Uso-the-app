<?php

namespace App\Repository;

use App\Entity\OsuUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OsuUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method OsuUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method OsuUser[]    findAll()
 * @method OsuUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OsuUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OsuUser::class);
    }

    // /**
    //  * @return OsuUser[] Returns an array of OsuUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OsuUser
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
