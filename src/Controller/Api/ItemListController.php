<?php

namespace App\Controller\Api;

use App\Entity\ItemList;
use App\Entity\User;
use App\Exception\JsonHttpException;
use App\Normalizer\ItemListNormalizer;
use App\Security\ApiAuthenticator;
use App\Services\LabelService;
use App\Services\ValidateService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ItemListController.
 *
 * @Route("/api/lists")
 */
class ItemListController extends AbstractController
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
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * ItemListController constructor.
     *
     * @param SerializerInterface $serializer
     * @param ValidateService     $validateService
     * @param PaginatorInterface  $paginator
     */
    public function __construct(SerializerInterface $serializer, ValidateService $validateService, PaginatorInterface $paginator)
    {
        $this->serializer = $serializer;
        $this->validateService = $validateService;
        $this->paginator = $paginator;
    }

    /**
     * @Route("", methods={"POST"}, name="api_list_add")
     */
    public function addAction(Request $request, LabelService $labelService)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneByApiToken($request->headers->get(ApiAuthenticator::X_API_KEY));

        /* @var ItemList $itemList */
        $itemList = $this->serializer->deserialize($request->getContent(), ItemList::class, 'json');
        $this->validateService->validate($itemList);
        $labelService->initLabels($itemList->getLabels(), $itemList);
        $this->validateService->validate($itemList->getLabels());
        $itemList->setUser($user);

        $this->getDoctrine()->getManager()->persist($itemList);
        $this->getDoctrine()->getManager()->flush();

        return $this->json($itemList);
    }

    /**
     * @Route("", methods={"GET"}, name="api_list_list")
     */
    public function listAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getDoctrine()->getRepository(User::class)->findOneByApiToken($request->headers->get(ApiAuthenticator::X_API_KEY));

        $startId = $request->query->has('startId') && $request->query->get('startId') > 0 ? $request->query->get('startId') : 1;
        $listsNumber = $request->query->has('listsNumber') && $request->query->get('listsNumber') > 0 ? $request->query->get('listsNumber') : 5;

        $lists = $this->getDoctrine()->getRepository(ItemList::class)->findAllByUser($user, $startId);

        return $this->json($this->paginator->paginate($lists, 1, $listsNumber));
    }

    /**
     * @Route("/{id}", methods={"DELETE"}, name="api_list_delete")
     */
    public function deleteAction(Request $request, ItemList $itemList)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneByApiToken($request->headers->get(ApiAuthenticator::X_API_KEY));
        if ($itemList->getUser() !== $user) {
            throw new JsonHttpException(400, 'Bad request');
        }
        $this->getDoctrine()->getManager()->remove($itemList);
        $this->getDoctrine()->getManager()->flush();

        return $this->json('ok');
    }

    /**
     * @Route("/{id}", methods={"PUT"}, name="api_list_edit")
     */
    public function editAction(Request $request, ItemList $itemList, LabelService $labelService)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneByApiToken($request->headers->get(ApiAuthenticator::X_API_KEY));

        if ($itemList->getUser() === $user) {
            /* @var ItemList $newItemList */
            $newItemList = $this->serializer->deserialize($request->getContent(), ItemList::class, 'json');
            $newTitle = $newItemList->getTitle();
            $newLabels = $newItemList->getLabels();

            $itemList->setTitle($newTitle);
            $this->validateService->validate($itemList);
            $labelService->syncLabels($newLabels, $itemList);
            $this->validateService->validate($itemList->getLabels());

            $this->getDoctrine()->getManager()->flush();
        } else {
            throw new JsonHttpException(400, 'Bad request');
        }

        return $this->json('ok');
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="api_list_show")
     */
    public function showAction(Request $request, ItemList $itemList)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneByApiToken($request->headers->get(ApiAuthenticator::X_API_KEY));
        if (!($itemList->getUser() === $user)) {
            throw new JsonHttpException(400, 'Bad request');
        }

        return $this->json($itemList, 200, [], [AbstractNormalizer::GROUPS => [ItemListNormalizer::GROUP_DETAILS]]);
    }
}
