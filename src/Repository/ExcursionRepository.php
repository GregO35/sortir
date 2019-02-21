<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Place;
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

    public function findByRegistered(User $user)
    {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.RegisterExcursion', 'u')
            ->addSelect('u')
            ->where('u.id = :id');
        $qb->setParameter('id',$user->getId());

        return $qb->getQuery()
            ->getResult();
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

    public function findPlace($id){

        $qb =  $this->createQueryBuilder('e');
        $qb ->join('e.place','p')
            ->andWhere('p.id=:id')
            ->setParameter('id',$id)
        ;

        $query = $qb->getQuery();
        $place =$query->getResult();

        return $place;
    }

    public function findCity($id){

        $qb =  $this->createQueryBuilder('e');
        $qb ->join('e.place','p')
            ->join('p.city', 'c')
            ->andWhere('c.id=:id')
            ->setParameter('id',$id)
        ;

        $query = $qb->getQuery();
        $city =$query->getResult();

        return $city;
    }

}
