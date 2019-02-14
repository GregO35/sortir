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


}
