<?php
/**
 * @description :
 * @package : PhpStorm.
 * @Author : quent
 * @date: 08/05/2018
 * @time: 09:49
 */

namespace App\Repository;

use App\Entity\TopImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TopImageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TopImage::class);
    }
}