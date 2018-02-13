<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

/**
 * Class UserRepository
 * @package App\Repository
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class UserRepository extends EntityRepository implements UserLoaderInterface
{
    /**
     * @var EntityManager|EntityManagerInterface
     */
    private $em;

    /**
     * UserRepository constructor.
     * @param EntityManager|EntityManagerInterface $em
     * @param ORM\ClassMetadata $class
     */
    public function __construct($em, ORM\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->em = $em;
    }

    /**
     * @param string $username
     * @return mixed
     */
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
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
