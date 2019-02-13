<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EncodeService
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * @param string|null $plainPassword
     * @param User        $user
     *
     * @return string|null
     */
    public function encodeUserPassword(?String $plainPassword, User $user)
    {
        return $this->userPasswordEncoder->encodePassword($user, $plainPassword);
    }
}
