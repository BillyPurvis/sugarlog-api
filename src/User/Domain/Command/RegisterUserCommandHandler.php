<?php

namespace App\User\Domain\Command;


use App\Entity\User;
use App\User\Domain\Repository\UserRepository;
use Monolog\Logger;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RegisterUserCommandHandler
 * @package App\User\Domain\Command
 */
class RegisterUserCommandHandler
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserPasswordEncoder
     */
    private $encoder;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * RegisterUserCommandHandler constructor.
     * @param Logger $logger
     * @param UserRepository $userRepository
     * @param UserPasswordEncoder $encoder
     * @param ValidatorInterface $validator
     */
    public function __construct(Logger $logger, UserRepository $userRepository, UserPasswordEncoder $encoder, ValidatorInterface $validator)
    {
        $this->logger = $logger;
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
        $this->validator = $validator;
    }

    /**
     * @param RegisterUserCommand $command
     */
    public function handle(RegisterUserCommand $command) {

        $email = $command->getEmail();
        $username = $command->getUsername();
        $password = $command->getPassword(); // TODO Validate Password require special chars etc

        // Create user
        $user = new User();

        // Validate
        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
        }

        // Validate Password
        $isPasswordValid = $this->encoder->isPasswordValid($user, $password);

        if (null !== $isPasswordValid) {
            // encode password
            $encodedPassword = $this->encoder->encodePassword($user, $password);

            $user->setEmail($email);
            $user->setUsername($username);
            $user->setPassword($encodedPassword);
            $this->userRepository->save($user);

            $this->logger->debug('CMD::RegisterUserCommand::User Created', (array)$user);
        }
        // FIXME Emit Event if there's any errors and passback to response
    }

}