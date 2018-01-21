<?php

namespace App\User\Api\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserRegistrationController
{
    /**
     * @Route("/register")
     * @return Response
     */
    public function registerUser() {
        return new Response(
            'This is where I\'d register users, IF I HAD ANY TO REGISTER'
        );
    }
}