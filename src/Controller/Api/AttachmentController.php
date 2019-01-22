<?php

namespace App\Controller\Api;

use App\Entity\Attachment;
use App\Entity\CheckList;
use App\Entity\Item;
use App\Exception\JsonHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/list/{checkList}/item/{item}/attachment")
 */
class AttachmentController extends AbstractController
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
    public function addAction(Request $request, CheckList $checkList, Item $item)
    {
        $user = $this->getUser();
        $userLists = $user->getCheckLists();
        if (isset($checkList, $userLists)) {
            $items = $checkList->getItems();
            if (isset($item, $items)) {
                $attachment = $this->serializer->deserialize($request->getContent(), Attachment::class, 'json');
                $errors = $this->validator->validate($attachment);
                if (count($errors)) {
                    throw new JsonHttpException(400, 'Bad Request');
                }
                $item->setAttachment($attachment);
                $em = $this->getDoctrine()->getManager();
                $em->persist($item);
                $em->flush();

                return $this->json(['item' => $item]);
            }
        }

        throw new JsonHttpException(400, 'Bad Request');
    }

    /**
     * @Route("/{attachment}", methods={"DELETE"})
     */
    public function removeAction(CheckList $checkList, Item $item, Attachment $attachment)
    {
        $user = $this->getUser();
        $userLists = $user->getCheckLists();
        if (isset($checkList, $userLists)) {
            $items = $checkList->getItems();
            if (isset($item, $items)) {
                if ($attachment === $item->getAttachment()) {
                    $item->setAttachment(null);
                    $em = $this->getDoctrine()->getManager();
                    $em->remove($attachment);
                    $em->persist($item);
                    $em->flush();

                    return $this->json(['user'=>$user]);
                }
            }
        }

        throw new JsonHttpException(400, 'Bad Request');
    }
}
