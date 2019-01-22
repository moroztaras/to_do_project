<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Exception\JsonHttpException;
use App\Services\PasswordEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var PasswordEncoder
     */
    private $passwordEncoder;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, PasswordEncoder $passwordEncoder)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("api/registration", methods={"POST"})
     */
    public function registrationAction(Request $request)
    {
        if (!$content = $request->getContent()) {
            throw new JsonHttpException(400, 'Bad Request');
        }
        /** @var User $user */
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');
        $errors = $this->validator->validate($user);
        if (count($errors)) {
            throw new JsonHttpException(400, 'Bad Request');
        }
        $this->passwordEncoder->index($user);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->json($user);
    }

    /**
     * @Route("api/login", methods={"POST"})
     */
    public function authorizeAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if (!$content = $request->getContent()) {
            throw new JsonHttpException(400, 'Bad Request');
        }
        $data = json_decode($content, true);
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        if ($user instanceof User) {
            if ($passwordEncoder->isPasswordValid($user, $data['password'])) {
                return $this->json($user);
            }
        }
        throw new JsonHttpException(400, 'Bad Request');
    }
}
