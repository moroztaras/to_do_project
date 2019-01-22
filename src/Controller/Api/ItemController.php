<?php

namespace App\Controller\Api;

use App\Entity\CheckList;
use App\Entity\Item;
use App\Exception\JsonHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/list/{checkList}/item")
 */
class ItemController extends AbstractController
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
    public function addAction(Request $request, CheckList $checkList)
    {
        if (!$content = $request->getContent()) {
            throw new JsonHttpException(400, 'Bad Request');
        }
        $item = $this->serializer->deserialize($request->getContent(), Item::class, 'json');
        $errors = $this->validator->validate($item);
        if (count($errors)) {
            throw new JsonHttpException(400, 'Bad Request');
        }
        $checkList->addItem($item);
        $em = $this->getDoctrine()->getManager();
        $em->persist($checkList);
        $em->flush();

        return $this->json(['item' => $item]);
    }

    /**
     * @Route("/{item}", methods={"DELETE"})
     */
    public function removeAction(CheckList $checkList, Item $item)
    {
        $user = $this->getUser();
        $userLists = $user->getCheckLists();
        if (isset($checkList, $userLists)) {
            $items = $checkList->getItems();
            if (isset($item, $items)) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($item);
                $em->flush();

                return $this->json(null, 200);
            }
        }
        throw new JsonHttpException(400, 'Bad Request');
    }

    /**
     * @Route("/{item}", methods={"PUT"})
     */
    public function updateAction(CheckList $checkList, Item $item)
    {
        $user = $this->getUser();
        $userLists = $user->getCheckLists();
        if (isset($checkList, $userLists)) {
            $items = $checkList->getItems();
            if ($items->contains($item)) {
                if ($item->getChecked()) {
                    $item->setChecked(false);
                } else {
                    $item->setChecked(true);
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($item);
                $em->flush();

                return $this->json(['item' => $item]);
            }
        }

        throw new JsonHttpException(400, 'Bad Request');
    }
}
