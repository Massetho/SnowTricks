<?php
/**
 * @description : Video repository
 * @Author : Quentin Thomasset
 */

namespace App\Repository;

use App\Entity\Video;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class VideoRepository extends ServiceEntityRepository
{
    /**
     * VideoRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Video::class);
    }
}
