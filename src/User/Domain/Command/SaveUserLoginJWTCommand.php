<?php

namespace App\User\Domain\Command;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;


/**
 * Class SaveUserLoginJWTCommand
 * @package App\User\Domain\Command
 */
class SaveUserLoginJWTCommand
{
    /**
     * @var User $user
     */
    private $user;

    /**
     * @var $token
     */
    private $token;

    /**
     * SaveUserLoginJWTCommand constructor.
     * @param User $user
     */
    public function __construct(User $user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return $token
     */
    public function getToken()
    {
        return $this->token;
    }

}