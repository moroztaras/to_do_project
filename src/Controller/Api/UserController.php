<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Model\Card;
use App\Services\UserService;
use App\Services\ValidateService;
use App\Exception\JsonHttpException;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Normalizer\UserNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Class UserController.
 *
 * @Route("/api/user")
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
     * @var ValidateService
     */
    private $validateService;

    /**
     * UserController constructor.
     *
     * @param SerializerInterface          $serializer
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param ValidatorInterface           $validator
     * @param ValidateService              $validateService
     */
    public function __construct(SerializerInterface $serializer, UserPasswordEncoderInterface $passwordEncoder, ValidatorInterface $validator, ValidateService $validateService)
    {
        $this->serializer = $serializer;
        $this->passwordEncoder = $passwordEncoder;
        $this->validator = $validator;
        $this->validateService = $validateService;
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

        return $this->json(['user' => $user], 200, [], [AbstractNormalizer::GROUPS => [UserNormalizer::GROUP_REGISTRATION]]);
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

        return $this->json(['user' => $user], 200, [], [AbstractNormalizer::GROUPS => [UserNormalizer::GROUP_REGISTRATION]]);
    }

    /**
     * @Route("", methods={"GET"}, name="api_user_this")
     */
    public function getUserAction()
    {
        return $this->json(['user' => $this->getUser()], 200, [], [AbstractNormalizer::GROUPS => [UserNormalizer::GROUP_PROFILE]]);
    }

    /**
     * @Route("/api/user/card", methods={"POST"}, name="api_user_card")
     */
    public function addCardAction(Request $request, UserService $userService)
    {
        /** @var Card $card */
        $card = $this->serializer->deserialize($request->getContent(), Card::class, 'json');
        $this->validateService->validate($card);

        $userService->saveCC($card, $this->getUser());

        return $this->json([]);
    }
}
