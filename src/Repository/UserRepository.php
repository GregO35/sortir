<?php

namespace App\Repository;
use App\Entity\Excursion;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function remove($id){
        $dql = "DELETE App\Entity\User u
                WHERE u.id = :id";

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter(':id', $id);
        $query->execute();
    }

    public function findParticipants($id){

        $qb =  $this->createQueryBuilder('u');

        $qb ->innerJoin('u.excursions','e')
            ->Where('e.id=:id')
            ->setParameter('id',$id)
        ;

        $query = $qb->getQuery();
        $participants = $query->getResult();

        return $participants;
    }
    /*public function findBy($username){

      /*  $qb= $this->createQueryBuilder('u');
        $qb->andWhere('u.username= :username');

        $query=$qb->getQuery();

        $user=$query->getResult();
        return $user;

        $dql = "SELECT u
                FROM App\Entity\User u 
                WHERE u.username = :username 
                ";

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('username',$username);
        $user = $query->getResult();

        return $user;

    }*/


    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
