<?php


namespace App\User\Domain\Event;

use App\User\Infrastructure\Service\UserMailerService;
use Monolog\Logger;

class UserRegisteredEventHandler
{

    /**
     * @var UserMailerService
     */
    private $userMailerService;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * UserRegisteredEventHandler constructor.
     * @param UserMailerService $userMailerService
     * @param Logger $logger
     */
    public function __construct(UserMailerService $userMailerService, Logger $logger)
    {
        // Doesn't do shit
        $this->userMailerService= $userMailerService;
        $this->logger = $logger;
    }

    /**
     * @param UserRegisteredEvent $event
     */
    public function notify(UserRegisteredEvent $event)
    {

        $this->userMailerService->sendUserWelcomeEmail($event->getTemplate(), $event->getEmail());

    }
}