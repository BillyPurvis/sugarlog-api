<?php

namespace App\User\Infrastructure\EventListener;

use App\Entity\User;
use App\User\Domain\Command\SaveUserLoginJWTCommand;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Monolog\Logger;
use SimpleBus\SymfonyBridge\Bus\CommandBus;


/**
 * Class JWTAuthenticationSuccessListener
 * @package App\User\Infrastructure\EventListener
 */
class JWTAuthenticationSuccessListener
{
    /**
     * @var CommandBus $commandBus
     */
    private $commandBus;

    /**
     * @var Logger $logger
     */
    private $logger;

    /**
     * JWTAuthenticationSuccessListener constructor.
     * @param CommandBus $commandBus
     * @param Logger $logger
     */
    public function __construct(CommandBus $commandBus, Logger $logger)
    {
        $this->commandBus = $commandBus;
        $this->logger = $logger;
    }

    /**
     * @param AuthenticationSuccessEvent $event
     * @return bool
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {

        $data = $event->getData();
        $token = $data['token'];
        $user = $event->getUser();

        if (!$user instanceof User) {
            return false;
        }

        // Save JWT for logout feature
        $this->commandBus->handle(new SaveUserLoginJWTCommand($user, $token));

        return true;
    }

}