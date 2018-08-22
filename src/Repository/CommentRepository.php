<?php
/**
 * @description :
 * @package : PhpStorm.
 * @Author : quent
 * @date: 17/04/2018
 * @time: 10:23
 */
namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CommentRepository extends ServiceEntityRepository
{

    /**
     * CommentRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @param int $trickId
     * @param int $lastId
     * @return Collection|Comment
     */
    public function getMoreItems($trickId, $lastId)
    {
        $qb = $this->createQueryBuilder('c');

        $qb->where('c.trick_id = :trick_id')
            ->andWhere('c.id < :lastId')
            ->setParameter('trick_id', $trickId)
            ->setParameter('lastId', $lastId)
            ->orderBy('c.id', 'DESC')
            ->setMaxResults(5);

        return $qb
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Trick $trick
     * @param null|int $lastId
     * @return Criteria
     */
    public static function commentsCriteria(Trick $trick, $lastId = null)
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('trick', $trick))
            ->orderBy(array("id" => Criteria::DESC))
            ->setFirstResult(0)
            ->setMaxResults(10)
        ;

        if ($lastId !== null) {
            $criteria->andWhere(Criteria::expr()->lt('id', $lastId));
        }

        return $criteria;
    }
}
