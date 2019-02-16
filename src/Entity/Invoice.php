<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Invoice implements \JsonSerializable
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Description should not be blank."
     * )
     * @Assert\Length(
     *      min = 3,
     *      max = 3000,
     *      minMessage = "Description must be at least {{ limit }} characters long",
     *      maxMessage = "Description cannot be longer than {{ limit }} characters"

     * )
     */
    private $description;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(
     *     message = "Price should not be blank."
     * )
     * @Assert\Type(
     *     type="integer",
     *     message = "Price must be integer type."
     * )
     */
    private $price;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Currency should not be blank."
     * )
     * @Assert\Currency(
     *     message = "Currency must be currency type."
     * )
     */
    private $currency;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stripeChargeId;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="invoices")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getStripeChargeId(): ?string
    {
        return $this->stripeChargeId;
    }

    public function setStripeChargeId(?string $stripeChargeId): self
    {
        $this->stripeChargeId = $stripeChargeId;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'description' => $this->getDescription(),
            'price' => $this->getPrice(),
            'currency' => $this->getCurrency(),
            'stripeChargeId' => $this->getStripeChargeId(),
        ];
    }
}
