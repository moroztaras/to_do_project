<?php

namespace App\Controller\Api;

use App\Entity\CheckList;
use App\Exception\JsonHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/checklist")
 */
class CheckListController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @Route("", methods={"POST"})
     */
    public function createAction(Request $request)
    {
        if (!$content = $request->getContent()) {
            throw new JsonHttpException(400, 'Bad Request');
        }
        $user = $this->getUser();
        $checklist = $this->serializer->deserialize($content, CheckList::class, 'json');
        $errors = $this->validator->validate($checklist);
        if (count($errors)) {
            throw new JsonHttpException(400, 'Bad Request');
        }
        $user->addCheckList($checklist);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->json(['checklist' => $checklist]);
    }

    /**
     * @Route("/{id}", methods={"PUT"})
     */
    public function editNameAction(Request $request, CheckList $checkList)
    {
        if (!$content = $request->getContent()) {
            throw new JsonHttpException(400, 'Bad Request');
        }
        $user = $this->getUser();
        $userLists = $user->getCheckLists();
        if (isset($checkList, $userLists)) {
            $data = json_decode($content, true);
            $checkList->setName($data['name']);
            $checkList->setExpire($data['expire']);
            $errors = $this->validator->validate($checkList);
            if (count($errors)) {
                throw new JsonHttpException(400, 'Bad Request');
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($checkList);
            $em->flush();

            return $this->json(['checklist' => $checkList]);
        }

        throw new JsonHttpException(400, 'Bad Request');
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     */
    public function deleteAction(CheckList $checkList)
    {
        $user = $this->getUser();
        $userLists = $user->getCheckLists();
        if (isset($checkList, $userLists)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($checkList);
            $em->flush();

            return $this->json(['checklist' => $checkList]);
        }

        throw new JsonHttpException(400, 'Bad Request');
    }
}
