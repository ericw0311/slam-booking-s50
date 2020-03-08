<?php

namespace App\Repository;

use App\Entity\UserParameter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method UserParameter|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserParameter|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserParameter[]    findAll()
 * @method UserParameter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserParameterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserParameter::class);
    }

}
