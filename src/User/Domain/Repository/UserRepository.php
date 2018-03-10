<?php

namespace App\User\Domain\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

/**
 * Class UserRepository
 * @package App\User\Domain\Repository
 */
class UserRepository implements UserLoaderInterface
{
    /**
     * @var EntityManager|EntityManagerInterface
     */
    private $em;


    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $username
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function loadUserByUsername($username)
    {
        return $this->em->createQueryBuilder('u')
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param $token
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function findByToken($token)
    {
        return $this->em->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.jwtToken = :token')
            ->setParameter('token', $token)
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();
    }

    /**
     * @param User $user
     */
    public function save(User $user)
    {
        $this->em->persist($user);
        $this->em->flush($user);
        $this->em->clear(User::class);
        return;
    }
}
