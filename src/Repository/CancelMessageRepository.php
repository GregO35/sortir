<?php

namespace App\Repository;

use App\Entity\CancelMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CancelMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method CancelMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method CancelMessage[]    findAll()
 * @method CancelMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CancelMessageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CancelMessage::class);
    }

    // /**
    //  * @return CancelMessage[] Returns an array of CancelMessage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CancelMessage
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
