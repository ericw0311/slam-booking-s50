<?php

namespace App\Repository;

use App\Entity\FileParameter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method FileParameter|null find($id, $lockMode = null, $lockVersion = null)
 * @method FileParameter|null findOneBy(array $criteria, array $orderBy = null)
 * @method FileParameter[]    findAll()
 * @method FileParameter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FileParameterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FileParameter::class);
    }

}
