<?php

namespace App\Normalizer;

use App\Entity\User;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserNormalizer implements NormalizerInterface
{
    /**
     * @param User  $user
     * @param null  $format
     * @param array $context
     *
     * @return array|bool|float|int|string
     */
    public function normalize($user, $format = null, array $context = [])
    {
        return [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'apiToken' => $user->getApiToken(),
        ];
    }

    public function supportsNormalization($user, $format = null)
    {
        return $user instanceof User;
    }
}
