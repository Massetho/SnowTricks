<?php
/**
 * @description :
 * @package : PhpStorm.
 * @Author : quent
 * @date: 17/04/2018
 * @time: 10:20
 */
namespace App\Repository;

use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TrickRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Trick::class);
    }

    public function getLastItems()
    {
        $qb = $this->createQueryBuilder('t');

        $qb->orderBy('t.id', 'DESC')
            ->setMaxResults( 2 );

        return $qb
            ->getQuery()
            ->getResult();
    }

    public function getMoreItems($lastId)
    {
        $qb = $this->createQueryBuilder('t');

        $qb->where('t.id < :lastId')
            ->setParameter('lastId', $lastId)
            ->orderBy('t.id', 'DESC')
            ->setMaxResults( 2 );

        return $qb
            ->getQuery()
            ->getResult();
    }
}