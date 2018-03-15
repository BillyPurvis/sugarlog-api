<?php


namespace App\User\Domain\Event;

use App\User\Domain\Command\LogOutUserCommand;
use App\User\Infrastructure\Service\UserMailerService;
use Monolog\Logger;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Component\Debug\Exception\UndefinedMethodException;
use Twig\Template;

class UserPasswordForgottenRequestEventHandler
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
     * @param UserPasswordForgottenRequestEvent $event
     */
    public function notify(UserPasswordForgottenRequestEvent $event)
    {
        $user = $event->getUser();
        $email = $user->getEmail();

        $this->logger->debug(__CLASS__, (array)$user);

        $template  = $this->template->render('emails/userPasswordForgottenEmail/index.html.twig');
        $this->userMailerService->sendUserPasswordForgottenEmail($template, $email);
    }
}