<?php

namespace App\User\Domain\Command;


use App\Entity\User;
use App\User\Domain\Repository\UserRepository;
use Monolog\Logger;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 * Class LogOutUserCommandHandler
 * @package App\User\Domain\Command
 */
class LogOutUserCommandHandler
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
     * LogOutUserCommandHandler constructor.
     * @param Logger $logger
     * @param UserRepository $userRepository
     */
    public function __construct(Logger $logger, UserRepository $userRepository)
    {
        $this->logger = $logger;
        $this->userRepository = $userRepository;
    }

    /**
     * @param LogOutUserCommand $command
     */
    public function handle(LogOutUserCommand $command)
    {
        $username = $command->getUsername();

        // FIXME See why this happens - still works
        $user = $this->userRepository->loadUserByUsername($username);

        if (!($user instanceof User)) {
            throw new UsernameNotFoundException();
        }

        $user->setJwtToken(null);
        $this->userRepository->save($user);

        $this->logger->debug('CMD::LogOutUserCommand::User Logged Out', (array)$user);
    }

}