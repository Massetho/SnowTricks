<?php
/**
 * @description : Token repository
 * @Author : Quentin Thomasset
 */

namespace App\Repository;

use App\Entity\Token;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TokenRepository extends ServiceEntityRepository
{
    /**
     * TokenRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Token::class);
    }

    /**
     * @param User $user
     * @param string $token
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getMailConfirmationToken($user, $token)
    {
        $qb = $this->createQueryBuilder('t')
            ->where('t.token = :token')
            ->andWhere('t.user = :user')
            ->setParameter('token', $token)
            ->setParameter('user', $user)
            ->getQuery();

        return $qb->setMaxResults(1)->getOneOrNullResult();
    }
}
