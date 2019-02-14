<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Excursion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Excursion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Excursion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Excursion[]    findAll()
 * @method Excursion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExcursionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Excursion::class);
    }

    public function remove($id){
        $dql = "DELETE App\Entity\Excursion e
                WHERE e.id = :id";

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter(':id', $id);
        $query->execute();
    }

    public function findAllByFilters($site, $name, $startDate, $endDate,
                 $organizer, $register, $notRegister, $passedExcursion, User $user)
    {
        $qb = $this->createQueryBuilder('e');

        if($startDate !== "")
        {
            $qb->orWhere('e.startDate > :startdate');
            $qb->setParameter('startdate', $startDate);
        }

        if($endDate !== "")
        {
            $qb->orWhere('e.startDate < :enddate');
            $qb->setParameter('enddate', $endDate);
        }

        if($organizer)
        {
            $qb->orWhere('e.organizer = :organizer');
            $qb->setParameter('organizer', $user);
        }

        if($register)
        {
            $qb->orWhere('e.id IN (:register)');
            $qb->setParameter('register', $user->getExcursions());
        }

        if($notRegister)
        {
            $qb->orWhere('e.id not in (:notRegister)');
            $qb->setParameter('notRegister', $user->getExcursions());
        }

        if($passedExcursion)
        {
            $qb->orWhere('e.startDate < :now');
            $qb->setParameter('now', date("Y-m-d H:i:s"));
        }

        if($name !== "")
        {
            $qb->andWhere('e.name LIKE :name');
            $qb->setParameter('name', "%".$name."%");
        }

        $query = $qb->getQuery();
        $excursions = $query->getResult();

        return $excursions;
    }
}
