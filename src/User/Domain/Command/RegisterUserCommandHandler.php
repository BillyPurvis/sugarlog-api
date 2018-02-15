<?php

namespace App\User\Domain\Command;
use App\Entity\User;
use App\Repository\UserRepository;
use Monolog\Logger;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Created by PhpStorm.
 * User: Billy
 * Date: 14/02/2018
 * Time: 22:24
 */
class RegisterUserCommandHandler
{
    private $logger;

    private $userRepository;

    private $encoder;

    public function __construct(Logger $logger, UserRepository $userRepository, UserPasswordEncoder $encoder)
    {
        $this->logger = $logger;
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
    }

    public function handle(RegisterUserCommand $command) {

        $email = $command->getEmail();
        $username = $command->getUsername();
        $password = $command->getPassword();

        // Create user
        $user = new User();

        // Validate //TODO Add Validator
//        $errors = $this->validator->validate($user);
//
//        if (count($errors) > 0) {
//            $errorsString = (string) $errors;
//        }

        // encode password
        $encodedPassword = $this->encoder->encodePassword($user, $password);
        // TODO use isPasswordValid() #https://symfony.com/doc/current/security/password_encoding.html

        $user->setEmail($email);
        $user->setUsername($username);
        $user->setPassword($encodedPassword);
        $this->userRepository->save($user);

        $this->logger->info('CMD::RegisterUserCommand');
    }

}