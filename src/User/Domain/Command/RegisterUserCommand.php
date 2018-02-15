<?php

namespace App\User\Domain\Command;
/**
 * Created by PhpStorm.
 * User: Billy
 * Date: 14/02/2018
 * Time: 22:24
 */
class RegisterUserCommand
{
    private $email;
    
    private $username;
    
    private $password;
    
    public function __construct($email, $username, $password)
    {
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
}