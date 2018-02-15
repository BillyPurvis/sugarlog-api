<?php

namespace App\User\Domain\Command;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

/**
 * Created by PhpStorm.
 * User: Billy
 * Date: 14/02/2018
 * Time: 22:24
 */
class RegisterUserCommandHandler
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(RegisterUserCommand $command) {
        $this->logger->info('Hello World Billy');
    }

}