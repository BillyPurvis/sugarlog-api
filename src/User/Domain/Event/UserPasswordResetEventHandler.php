<?php


namespace App\User\Domain\Event;

use App\User\Domain\Command\LogOutUserCommand;
use App\User\Infrastructure\Service\UserMailerService;
use Monolog\Logger;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Component\Debug\Exception\UndefinedMethodException;
use Twig\Template;

class UserPasswordResetEventHandler
{

    /**
     * @var UserMailerService
     */
    private $userMailerService;

    /**
     * @var \Twig_Environment
     */
    private $template;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * UserPasswordResetEventHandler constructor.
     * @param UserMailerService $userMailerService
     * @param \Twig_Environment $template
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
     * @param UserPasswordResetEvent $event
     */
    public function notify(UserPasswordResetEvent $event)
    {
        $user = $event->getUser();
        $email = $user->getEmail();

        $template  = $this->template->render('emails/userPasswordWasResetEmail/index.html.twig');
        $this->userMailerService->sendUserPasswordWasResetEmail($template, $email);
    }
}