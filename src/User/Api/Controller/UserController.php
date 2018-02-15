<?php

namespace App\User\Api\Controller;

use App\Entity\User;
use App\User\Domain\Command\RegisterUserCommand;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class UserController extends AbstractController
{
    protected $logger;
    protected $validator;
    protected $encoder;
    protected $commandBus;

    function __construct(
        LoggerInterface $logger,
        ValidatorInterface $validator,
        UserPasswordEncoderInterface $encoder,
        CommandBus $commandBus
    )
    {
        $this->logger = $logger;
        $this->validator = $validator;
        $this->encoder = $encoder;
        $this->commandBus = $commandBus;
    }

    /**
     * @Route("/user/register")
     * @Method("POST")
     * @return Response
     */
    public function registerUser(Request $request) {

        $userRepository = $this->getDoctrine()
            ->getRepository(User::class);

        $requestBody = $request->getContent();

        $response = new Response();

        // TODO Make as Command (Bus)
        if (null != $requestBody) {
            // Process Response
            $requestJson = \json_decode($requestBody);

            $email = $requestJson->email;
            $username = $requestJson->username; // TODO Validate
            $password = $requestJson->password; // TODO Salt + Hash

            // Create user // TODO Move to Command / Factory (?)
            $user = new User();

            // Validate
            $errors = $this->validator->validate($user);

            if (count($errors) > 0) {
                $errorsString = (string) $errors;
            }

            // encode password
            $encodedPassword = $this->encoder->encodePassword($user, $password);
            // TODO use isPasswordValid() #https://symfony.com/doc/current/security/password_encoding.html

            $user->setEmail($email);
            $user->setUsername($username);
            $user->setPassword($encodedPassword);
            $userRepository->save($user);

            // Register User Command Sent
            $this->commandBus->handle(new RegisterUserCommand());

            // return response
            $response->setStatusCode(Response::HTTP_OK);
            return $response->send();

        }
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        return $response->send();
    }

    /**
     * @Route("/user/login", name="login")
     * @param Request $request
     */
    public function loginUser(Request $request) {
       // Returns token
    }
}