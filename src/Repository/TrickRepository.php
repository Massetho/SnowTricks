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
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TrickRepository extends ServiceEntityRepository
{
    /**
     * TrickRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Trick::class);
    }

    /**
     * @return mixed
     */
    public function getLastItems()
    {
        $qb = $this->createQueryBuilder('t');

        $qb->orderBy('t.id', 'DESC')
            ->setMaxResults(12);

        return $qb
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $lastId
     * @return mixed
     */
    public function getMoreItems($lastId)
    {
        $qb = $this->createQueryBuilder('t');

        $qb->where('t.id < :lastId')
            ->setParameter('lastId', $lastId)
            ->orderBy('t.id', 'DESC')
            ->setMaxResults(6);

        return $qb
            ->getQuery()
            ->getResult();
    }
}
