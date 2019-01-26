<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 * @ORM\EntityListeners({"App\Listener\ItemListener"})
 */
class Item implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 5,
     *      max = 15,
     *      minMessage = "Item name must be at least {{ limit }} characters long",
     *      maxMessage = "Item name cannot be longer than {{ limit }} characters"
     * )
     * @Assert\NotNull(
     *     message = "Item name should not be blank"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isChecked;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $expiration;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ItemList", inversedBy="items")
     * @ORM\JoinColumn(nullable=false)
     */
    private $list;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Attachment", cascade={"persist", "remove"})
     */
    private $attachment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getIsChecked(): ?bool
    {
        return $this->isChecked;
    }

    public function setIsChecked(bool $isChecked): self
    {
        $this->isChecked = $isChecked;

        return $this;
    }

    public function getExpiration(): ?\DateTimeInterface
    {
        return $this->expiration;
    }

    public function setExpiration(?\DateTimeInterface $expiration): self
    {
        $this->expiration = $expiration;

        return $this;
    }

    public function getList(): ?ItemList
    {
        return $this->list;
    }

    public function setList(?ItemList $list): self
    {
        $this->list = $list;

        return $this;
    }

    public function getAttachment(): ?Attachment
    {
        return $this->attachment;
    }

    public function setAttachment(?Attachment $attachment): self
    {
        $this->attachment = $attachment;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'expiration' => $this->getExpiration(),
            'isChecked' => $this->getIsChecked(),
            'attachment' => $this->getAttachment(),
        ];
    }
}
