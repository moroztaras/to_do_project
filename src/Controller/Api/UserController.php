<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Exception\JsonHttpException;
use App\Services\ValidateService;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/user")
 */
class UserController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ValidateService
     */
    private $validateService;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(SerializerInterface $serializer, ValidateService $validateService, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->serializer = $serializer;
        $this->validateService = $validateService;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/registration", methods={"POST"}, name="api_user_registration")
     */
    public function registrationAction(Request $request)
    {
        /* @var User $user */
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');
        $this->validateService->validate($user);
        $user->setApiToken(Uuid::uuid4());

        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        return $this->json(['user' => $user]);
    }

    /**
     * @Route("/login", methods={"POST"}, name="api_user_login")
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
