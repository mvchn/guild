<?php

namespace App\Repository;

use App\Entity\OrderAttribute;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderAttribute|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderAttribute|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderAttribute[]    findAll()
 * @method OrderAttribute[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderAttributeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderAttribute::class);
    }

    // /**
    //  * @return OrderAttribute[] Returns an array of OrderAttribute objects
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
    public function findOneBySomeField($value): ?OrderAttribute
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
