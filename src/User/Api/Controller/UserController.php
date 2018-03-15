<?php

namespace App\User\Api\Controller;

use App\Entity\User;
use App\User\Domain\Command\LogOutUserCommand;
use App\User\Domain\Command\RegisterUserCommand;
use App\User\Domain\Event\UserPasswordResetEvent;
use App\User\Domain\Event\UserRegisteredEvent;
use App\User\Domain\Repository\UserRepository;
use App\User\Infrastructure\Service\UserMailerService;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use SimpleBus\SymfonyBridge\Bus\EventBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UserController
 * @package App\User\Api\Controller
 */
class UserController extends AbstractController implements TokenAuthenticationController
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var UserPasswordEncoderInterface
     */
    protected $encoder;

    /**
     * @var CommandBus
     */
    protected $commandBus;

    /**
     * @var EventBus
     */
    protected $eventBus;

    /*
     * @var UserMailerService
     */
    protected $mailer;

    /**
     * @var UserRepository
     */
    private $userRepository;


    /**
     * UserController constructor.
     * @param LoggerInterface $logger
     * @param ValidatorInterface $validator
     * @param UserPasswordEncoderInterface $encoder
     * @param CommandBus $commandBus
     * @param UserMailerService $mailerService
     */
    function __construct(
        LoggerInterface $logger,
        ValidatorInterface $validator,
        UserPasswordEncoderInterface $encoder,
        CommandBus $commandBus,
        EventBus $eventBus,
        UserMailerService $mailerService,
        UserRepository $userRepository
    )
    {
        $this->logger = $logger;
        $this->validator = $validator;
        $this->encoder = $encoder;
        $this->commandBus = $commandBus;
        $this->eventBus = $eventBus;
        $this->mailer = $mailerService;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/api/register")
     * @Method("POST")
     * @return Response
     */
    public function registerUser(Request $request) {

        $requestBody = $request->getContent();

        $response = new Response();

        if (null != $requestBody) {
            // Process Response
            $requestJson = \json_decode($requestBody);

            $email = $requestJson->email;
            $username = $requestJson->username;
            $password = $requestJson->password;

            // Register User Command Sent
            $this->commandBus->handle(new RegisterUserCommand($username, $email, $password));

            // FIXME Use Event Registering and send only if command is successful
            // Send Event Email
            $this->eventBus->handle(new UserRegisteredEvent($email));


            // return response
            $response->setStatusCode(Response::HTTP_OK);
            return $response->send();

        }
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        return $response->send();
    }

    /**
     * @Route("/api/login", name="login")
     * @param Request $request
     */
    public function loginUser(Request $request) {
       // Returns token
    }

    /**
     * @Route("/api/logout", name="logout")
     * @param Request $request
     */
    public function logOutUser(Request $request) {

        /**
         * @var User $user;
         */
        $user = $this->getUser();
        $username = $user->getUsername();

        $this->commandBus->handle(new LogOutUserCommand($username));

        $response = new Response();
        $response->setStatusCode(200);
        $response->setContent('Success');

        return $response;
    }

    /**
     * @Route("api/password-change")
     * @Method("POST")
     * @param Response $response
     */
    public function passwordReset(Request $request)
    {
        $user = $this->getUser();

        $requestBody = $request->getContent();
        $requestJson = \json_decode($requestBody);
        $password = $requestJson->password;
        $newPassword = $requestJson->newPassword;

        $res = new Response();

        if (!($user instanceof User)) {
            return $res->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        if (!$password) {
            return $res->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $isPasswordValid = $this->encoder->isPasswordValid($user, $password);
        if(!$isPasswordValid) {
            return $res->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        // TODO Move to command
        // Reset Password
        $encodedPassword = $this->encoder->encodePassword($user, $newPassword);
        $user->setPassword($encodedPassword);

        $this->userRepository->save($user);

        // TODO log user out
        //$this->commandBus->handle(new LogOutUserCommand($user));

        $this->eventBus->handle(new UserPasswordResetEvent($user));
        return $res->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @Route("api/password-forgotten")
     * @param Request $request
     */
    public function passwordForgotten(Request $request) {
        $res = new Response();

        $res->setContent('Open');

        return $res;

    }

    /**
     * @Route("mail")
     * @param Response $response
     */
    public function mail(Request $request)
    {
        $res = new Response();

        $res->setContent('Test Email');
        return $res;
    }
}