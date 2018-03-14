<?php


namespace App\User\Domain\Event;

use App\Entity\User;

class UserPasswordResetEvent
{

    /**
     * @var $user
     */
    private $user;

    public function __construct($user)
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