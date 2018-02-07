<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
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
//    public function findBySomething($value)
//    {
//        return $this->createQueryBuilder('u')
//            ->where('u.something = :value')->setParameter('value', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function save(User $user, EntityManager $em) {
        $em->persist($user);
        $em->flush($user);
        $em->clear($user);
        return;
    }
}
