<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\ItemList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function findOneByParams(int $id, ?ItemList $itemList): ?Item
    {
        return $this->createQueryBuilder('item')
            ->where("item.list = {$itemList->getId()}")
            ->where("item.id = $id")
            ->getQuery()
            ->getOneOrNullResult();
    }
}
