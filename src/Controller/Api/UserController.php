<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Exception\JsonHttpException;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UserController.
 *
 * @Route("/api/users")
 */
class UserController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * UserController constructor.
     *
     * @param SerializerInterface          $serializer
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param ValidatorInterface           $validator
     */
    public function __construct(SerializerInterface $serializer, UserPasswordEncoderInterface $passwordEncoder, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->passwordEncoder = $passwordEncoder;
        $this->validator = $validator;
    }

    /**
     * @Route("", methods={"POST"}, name="api_user_registration")
     */
    public function registrationAction(Request $request)
    {
        if (!$content = $request->getContent()) {
            throw new JsonHttpException(400, 'Bad Request');
        }
        /* @var User $user */
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');
        $user
          ->setRoles(['ROLE_USER'])
          ->setApiToken(Uuid::uuid4())
          ->setPassword($this->passwordEncoder->encodePassword($user, $user->getPlainPassword()))
        ;

        $errors = $this->validator->validate($user);
        if (count($errors)) {
            throw new JsonHttpException(400, (string) $errors->get(0)->getPropertyPath().': '.(string) $errors->get(0)->getMessage());
        }
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        return $this->json(['user' => $user]);
    }

    /**
     * @Route("/login", methods={"POST"}, name="api_users_login")
     */
    public function loginAction(Request $request)
    {
        /* @var User $user */
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');
        $plainPassword = $user->getPlainPassword();
        $user = $this->getDoctrine()->getRepository(User::class)->findOneByEmail($user->getEmail());
        if (!$this->passwordEncoder->isPasswordValid($user, $plainPassword)) {
            throw new JsonHttpException(400, JsonHttpException::AUTH_ERROR);
        }
        $user->setApiToken(Uuid::uuid4());
        $this->getDoctrine()->getManager()->flush();

        return $this->json(['user' => $user]);
    }
}
