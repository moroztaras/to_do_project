<?php

namespace App\Repository;

use App\Entity\ItemList;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ItemList|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemList|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemList[]    findAll()
 * @method ItemList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemListRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ItemList::class);
    }

    public function findAllByUser(?User $user, int $startId): ?Query
    {
        return $this->createQueryBuilder('itemList')
            ->where('itemList.user = '.$user->getId())
            ->andWhere("itemList.id >= $startId")
            ->orderBy('itemList.id', 'ASC')
            ->getQuery();
    }

    public function findOneById(int $id, ?User $user): ?ItemList
    {
        return $this->createQueryBuilder('itemList')
            ->where('itemList.id = '.$id)
            ->andWhere('itemList.user = '.$user->getId())
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllByLabel(int $labelId, ?User $user, int $startId): ?Query
    {
        $qb = $this->createQueryBuilder('itemList');

        return $qb
            ->where('itemList.user = '.$user->getId())
            ->andWhere("itemList.id >= $startId")
            ->andWhere(':labelId MEMBER OF itemList.labels')
            ->setParameter('labelId', $labelId)
            ->orderBy('itemList.id', 'ASC')
            ->getQuery();
    }
}
