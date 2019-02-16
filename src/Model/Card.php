<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class Card implements \JsonSerializable
{
    /**
     * @var int
     *          Assert\CardScheme(schemes={"AMEX", "CHINA_UNIONPAY", "DINERS", "DISCOVER", "INSTAPAYMENT", "JCB", "LASER", "MAESTRO", "MASTERCARD", "VISA"})
     * @Assert\NotBlank(
     *     message="Card number value should not be blank."
     * )
     */
    private $number;

    /**
     * @var string
     * @Assert\NotBlank(
     *     message="Expiration month value should not be blank."
     * )
     * @Assert\Regex("/[0-9]+/")
     * @Assert\Length(
     *     min=1,
     *     max=2,
     *     minMessage="Expiration month value is too short. It should have {{ limit }} characters or less.",
     *     maxMessage="Expiration month value is too long. It should have {{ limit }} characters or less."
     * )
     * @Assert\Range(
     *     min=1,
     *     max=12,
     *     minMessage="Expiration month value should be {{ limit }} or more.",
     *     maxMessage="Expiration month value should be {{ limit }} or less."
     * )
     */
    private $expirationMonth;

    /**
     * @var string
     * @Assert\NotBlank(
     *     message="Expiration year value should not be blank."
     * )
     * @Assert\Length(
     *     min=4,
     *     max=4,
     *     minMessage="Expiration year value is too short. It should have {{ limit }} characters or less.",
     *     maxMessage="Expiration year value is too long. It should have {{ limit }} characters or less."
     * )
     * @Assert\Regex("/[0-9]+/")
     */
    private $expirationYear;

    /**
     * @var string
     * @Assert\NotBlank(
     *     message="CVC value should not be blank."
     * )
     * @Assert\Length(
     *     min=3,
     *     max=4,
     *     minMessage="CVC value is too short. It should have {{ limit }} characters or less.",
     *     maxMessage="CVC value is too long. It should have {{ limit }} characters or less."
     * )
     * @Assert\Regex("/[0-9]+/")
     */
    private $cvc;

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber(int $number): void
    {
        $this->number = $number;
    }

    /**
     * @return string
     */
    public function getExpirationMonth(): string
    {
        return $this->expirationMonth;
    }

    /**
     * @param string $expirationMonth
     */
    public function setExpirationMonth(string $expirationMonth): void
    {
        $this->expirationMonth = $expirationMonth;
    }

    /**
     * @return string
     */
    public function getExpirationYear(): string
    {
        return $this->expirationYear;
    }

    /**
     * @param string $expirationYear
     */
    public function setExpirationYear(string $expirationYear): void
    {
        $this->expirationYear = $expirationYear;
    }

    /**
     * @return string
     */
    public function getCvc(): string
    {
        return $this->cvc;
    }

    /**
     * @param string $cvc
     */
    public function setCvc(string $cvc): void
    {
        $this->cvc = $cvc;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ($this->expirationYear < (new \DateTime())->format('Y')) {
            $context
                ->buildViolation('Year is in past')
                ->atPath('expirationYear')
                ->addViolation();

            return;
        } elseif ($this->expirationYear > (new \DateTime('+10 years'))->format('Y')) {
            $context
                ->buildViolation('Year is too big')
                ->atPath('expirationYear')
                ->addViolation();

            return;
        }

        $expirationDate = new \DateTime($this->expirationYear.'-'.$this->expirationMonth);
        if ($expirationDate < new \DateTime()) {
            $context->buildViolation('This card has expired.')
                ->addViolation();
        }
    }

    public function jsonSerialize()
    {
        return [
            'cvc' => $this->getCvc(),
            'expiration month' => $this->getExpirationMonth(),
            'expiration year' => $this->getExpirationYear(),
            'number' => $this->getNumber(),
        ];
    }
}
