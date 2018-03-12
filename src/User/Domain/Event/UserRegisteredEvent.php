<?php


namespace App\User\Domain\Event;

use Twig\Template;

class UserRegisteredEvent
{
    /**
     * @var $email
     */
    private $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}