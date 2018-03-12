<?php


namespace App\User\Domain\Event;

use Twig\Template;

class UserRegisteredEvent
{
    /**
     * @var Template
     */
    private $template;

    /**
     * @var $email
     */
    private $email;

    public function __construct($template, $email)
    {
        $this->template = $template;
        $this->email = $email;
    }

    /**
     * @return Template
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}