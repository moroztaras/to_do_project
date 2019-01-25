<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EncodeService
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param String|null $plainPassword
     * @param User $user
     * @return string|null
     */
    public function encodeUserPassword(?String $plainPassword, User $user)
    {
        if (!empty($plainPassword)) {
            return $this->encoder->encodePassword($user, $plainPassword);
        }

        return $plainPassword;
    }
}
