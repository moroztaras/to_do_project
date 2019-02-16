<?php

namespace App\Services;

use App\Entity\User;
use App\Model\Card;
use Doctrine\Common\Persistence\ManagerRegistry;

class UserService
{
    /**
     * @var StripeService
     */
    private $stripeService;

    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    public function __construct(StripeService $stripeService, ManagerRegistry $doctrine)
    {
        $this->stripeService = $stripeService;
        $this->doctrine = $doctrine;
    }

    public function saveCC(Card $card, User $user)
    {
        if (!$user->getStripeCustomerId()) {
            $this->stripeService->createStripeCustomer($user);
            $this->doctrine->getManager()->flush();
        }

        $this->stripeService->addCardToCustomer($card, $user);

        $user->setCard('**** **** **** '.substr($card->getNumber(), strlen($card->getNumber()) - 4));
        $this->doctrine->getManager()->flush();

        return $user;
    }
}
