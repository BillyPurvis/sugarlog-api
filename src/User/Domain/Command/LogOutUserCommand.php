<?php

namespace App\User\Domain\Command;
use App\Entity\User;

/**
 * Class LogOutUserCommand
 * @package App\User\Domain\Command
 */
class LogOutUserCommand
{
    /**
     * @var User $user
     */
    private $user;

    /**
     * LogOutUserCommand constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

}