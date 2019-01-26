<?php

namespace App\Repository;

use App\Entity\User;
use App\Exception\JsonHttpException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param string|null $email
     *
     * @return User|null
     */
    public function findOneByEmail(?string $email): ?User
    {
        $user = $this->findOneBy(['email' => $email]);
        if (!$user) {
            throw new JsonHttpException(400, JsonHttpException::AUTH_ERROR);
        }
        return $user;
    }

    /**
     * @param string|null $apiToken
     *
     * @return User|null
     */
    public function findOneByApiToken(?string $apiToken): ?User
    {
        $user = $this->findOneBy(['apiToken' => $apiToken]);
        if (!$user) {
            throw new JsonHttpException(400, JsonHttpException::AUTH_ERROR);
        }
        return $user;
    }
}
