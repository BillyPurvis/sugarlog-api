<?php

namespace App\User\Infrastructure\EventListener;


use App\Entity\User;
use App\Repository\UserRepository;
use App\User\Api\Controller\TokenAuthenticationController;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class TokenAuthenticationSubscriber
 * @package App\User\Infrastructure\EventListener
 */
class TokenAuthenticationSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserRepository $userRepository
     */
    private $userRepository;
    /**
     * @var Logger $logger
     */
    private $logger;

    /**
     * TokenAuthenticationSubscriber constructor.
     * @param Logger $logger
     */
    public function __construct(UserRepository $userRepository, LoggerInterface $logger)
    {
        $this->userRepository = $userRepository;
        $this->logger = $logger;
    }

    /**
     * @param FilterControllerEvent $event
     * Checks to see if the JWT Token persists in the DB, returns 403 meaning user is invalidated
     * Logged out
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof TokenAuthenticationController) {

            // Get auth token
            $tokenHeader = $event->getRequest()->headers->get('authorization');

            if (null != $tokenHeader) {
                $token = preg_replace('/\bBearer\s\b/', '', $tokenHeader);
                // Attempt to find valid JWT to user

                $user = $this->userRepository->findByToken($token);

                if (!$user instanceof User) {
                    // JWT Doesn't exist OR invalid
                    throw new AccessDeniedHttpException('This action needs a valid token');
                }

                $this->logger->debug('TokenAuthenticationController::JWT FOUND');
            }
        }

    }

    /**
     *
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController'
        );
    }

}