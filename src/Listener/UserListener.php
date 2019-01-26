<?php

namespace App\Listener;

use App\Entity\User;
use App\Services\EncodeService;
use Doctrine\ORM\Mapping\PreFlush;

class UserListener
{
    /**
     * @var EncodeService
     */
    private $userService;

    /**
     * UserListener constructor.
     *
     * @param $userService
     */
    public function __construct(EncodeService $userService)
    {
        $this->userService = $userService;
    }

    /** @PreFlush */
    public function preFlushHandler(User $user)
    {
        if (null != $user->getPlainPassword()) {
            $user->setPassword($this->userService->encodeUserPassword($user->getPlainPassword(), $user));
        }
    }
}
