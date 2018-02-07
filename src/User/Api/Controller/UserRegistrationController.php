<?php

namespace App\User\Api\Controller;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/user")
 */
class UserRegistrationController extends AbstractController
{
    protected $logger;
    protected $validator;
    protected $encoder;

    function __construct(
        LoggerInterface $logger,
        ValidatorInterface $validator,
        UserPasswordEncoderInterface $encoder
    )
    {
        $this->logger = $logger;
        $this->validator = $validator;
        $this->encoder = $encoder;
    }

    /**
     * @Route("/register")
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

            // return response
            $response->setStatusCode(Response::HTTP_OK);
            return $response->send();

        }
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        return $response->send();
    }

    /**
     * @Route("/admin")
     * @return Response
     */
    public function admin() {
        return new Response('<html><body>Admin Page!</body></html>');
    }

    /**
     * @Route("/test")
     */
    public function test() {

        /**
         * @var User $user
         */
        $user = $this->getUser();

        return new JsonResponse([
            'id'    => $user->getId(),
            'user'  => $user->getUsername(),
            'email' => $user->getEmail()
        ]);
    }
}