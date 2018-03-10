<?php

namespace App\User\Domain\Command;
use App\Entity\User;
use App\User\Domain\Repository\UserRepository;
use Monolog\Logger;

/**
 * Class RegisterUserCommandHandler
 * @package App\User\Domain\Command
 */
class SaveUserLoginJWTCommandHandler
{
    private $logger;

    /**
     * SaveUserLoginJWTCommandHandler constructor.
     * @param Logger $logger
     * @param UserRepository $userRepository
     * @return bool
     */
    public function __construct(Logger $logger, UserRepository $userRepository)
    {
        $this->logger = $logger;
        $this->userRepository = $userRepository;
    }

    public function handle(SaveUserLoginJWTCommand $command) {

        $user = $command->getUser();
        $token = $command->getToken();

        if (!$user instanceof User || !$token) {
            return false;
        }

        $user->setJwtToken($token);
        $this->userRepository->save($user);

        // We have the user
        $this->logger->info('SaveUserLoginJWTCommand::', (array)$user);

        return true;
    }

}