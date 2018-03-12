<?php


namespace App\User\Domain\Event;

use App\User\Infrastructure\Service\UserMailerService;
use Monolog\Logger;
use Symfony\Component\Debug\Exception\UndefinedMethodException;
use Twig\Template;

class UserRegisteredEventHandler
{

    /**
     * @var UserMailerService
     */
    private $userMailerService;

    private $template;
    /**
     * @var Logger
     */
    private $logger;

    /**
     * UserRegisteredEventHandler constructor.
     * @param UserMailerService $userMailerService
     * @param Logger $logger
     */
    public function __construct(UserMailerService $userMailerService, \Twig_Environment $template, Logger $logger)
    {
        // Doesn't do shit
        $this->userMailerService= $userMailerService;
        $this->template = $template;
        $this->logger = $logger;
    }

    /**
     * @param UserRegisteredEvent $event
     */
    public function notify(UserRegisteredEvent $event)
    {


        $template  = $this->template->render('emails/userRegisteredEmail/index.html.twig', array('name' => 'billy'));
        $this->userMailerService->sendUserWelcomeEmail($template, $event->getEmail());

    }
}