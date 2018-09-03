<?php
/**
 * @description : Group repository
 * @Author : Quentin Thomasset
 */

namespace App\Repository;

use App\Entity\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class GroupRepository
 * @package App\Repository
 */
class GroupRepository extends ServiceEntityRepository
{
    /**
     * GroupRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Group::class);
    }
}
