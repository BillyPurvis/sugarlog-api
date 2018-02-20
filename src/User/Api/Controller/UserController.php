<?php

namespace App\User\Api\Controller;

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
     * UserController constructor.
     * @param LoggerInterface $logger
     * @param ValidatorInterface $validator
     * @param UserPasswordEncoderInterface $encoder
     * @param CommandBus $commandBus
     */
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

    /**
     * @Route("/api/test")
     * @return JsonResponse
     * Test Controller to check auth
     */
    public function test(Request $request)
    {
        $user = $this->getUser();

        return new JsonResponse([
            'id' => $user->getId(),
            'name' => $user->getUsername()
        ]);
    }
}