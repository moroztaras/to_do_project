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
    private $encodeService;

    /**
     * UserListener constructor.
     *
     * @param EncodeService $encodeService
     */
    public function __construct(EncodeService $encodeService)
    {
        $this->encodeService = $encodeService;
    }

    /** @PreFlush */
    public function preFlushHandler(User $user)
    {
        if (null != $user->getPlainPassword()) {
            $user->setPassword($this->encodeService->encodeUserPassword($user->getPlainPassword(), $user));
        }
    }
}
