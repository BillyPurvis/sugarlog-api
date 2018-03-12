<?php
/**
 * Created by PhpStorm.
 * User: Billy
 * Date: 10/03/2018
 * Time: 19:39
 */

namespace App\User\Infrastructure\Service;


use App\Entity\User;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Twig\Template;

/**
 * Class UserMailerService
 * @package App\User\Infrastructure\Service
 */
class UserMailerService
{
    /**
     * @var \Swift_Mailer $mailer
     */
    private $mailer;

    /**
     * @var Logger $logger
     */
    private $logger;
    /**
     * UserMailerService constructor.
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    /**
     * @param User $user
     */
    public function sendUserWelcomeEmail($template, $email)
    {

        // TODO Pass User
        $message = (new \Swift_Message('Welcome To SugarLog'))
            ->setFrom('info@sugarlog.co.uk') // FIXME Make config param
            ->setTo($email)
            ->setBody($template, 'text/html');

            $this->mailer->send($message);

            // TODO Add userId
            $this->logger->info('sendUserWelcomeEmailService Sent::');
    }
}