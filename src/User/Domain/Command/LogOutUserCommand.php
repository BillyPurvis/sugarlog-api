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
     * @var $username
     */
    private $username;

    /*
     * LogOutUserCommand
     */
    public function __construct($username)
    {
        $this->username = $username;
    }

    /**
     * @return User
     */
    public function getUsername()
    {
        return $this->username;
    }

}