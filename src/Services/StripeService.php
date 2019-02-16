<?php

namespace App\Services;

use App\Entity\Invoice;
use App\Entity\User;
use App\Model\Card;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Token;

class StripeService
{
    /**
     * @var string
     */
    private $stripeSecretKey;

    public function __construct(string $stripeSecretKey)
    {
        $this->stripeSecretKey = $stripeSecretKey;
    }

    public function createStripeCustomer(User $user)
    {
        $customer = Customer::create(
            [
                'description' => "Customer for {$user->getEmail()}",
                'email' => $user->getEmail(),
            ],
            ['api_key' => $this->stripeSecretKey]
        );

        return $user->setStripeCustomerId($customer->id);
    }

    public function addCardToCustomer(Card $card, User $user)
    {
        $token = Token::create(
            [
                'card' => [
                    'number' => $card->getNumber(),
                    'exp_month' => $card->getExpirationMonth(),
                    'exp_year' => $card->getExpirationYear(),
                    'cvc' => $card->getCvc(),
                ],
            ],
            ['api_key' => $this->stripeSecretKey]
        );

        $customer = Customer::retrieve(
            $user->getStripeCustomerId(),
            ['api_key' => $this->stripeSecretKey]
        );

        $stripeCard = $customer->sources->create(['source' => $token->id]);
        $customer->default_source = $stripeCard->id;

        $customer->save();

        if ($customer->sources->total_count > 1) {
            foreach ($customer->sources->data as $stripeCard) {
                if ($stripeCard->id != $customer->default_source) {
                    $stripeCard->delete();
                }
            }
        }

        return $user;
    }

    public function createChargeForInvoice(Invoice $invoice)
    {
        $charge = Charge::create(
            [
                'amount' => $invoice->getPrice(),
                'currency' => $invoice->getCurrency(),
                'customer' => $invoice->getUser()->getStripeCustomerId(),
                'description' => 'Charge for the invoice. ID: '.$invoice->getId(),
            ],
            ['api_key' => $this->stripeSecretKey]
        );

        $invoice->setStripeChargeId($charge->id);

        return $invoice;
    }
}
