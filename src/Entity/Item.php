<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation\SoftDeleteable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 * @SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ORM\EntityListeners({"App\Listener\ItemEntityListener"})
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
     * @Assert\NotNull(
     *     message = "Item title should not be blank"
     * )
     * @Assert\NotBlank(
     *     message = "Item title should not be blank"
     * )
     * @Assert\Length(
     *      min = 5,
     *      max = 20,
     *      minMessage = "Item title must be at least {{ limit }} characters long",
     *      maxMessage = "Item title cannot be longer than {{ limit }} characters"
     * )
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ItemList", inversedBy="items")
     * @ORM\JoinColumn(nullable=false)
     */
    private $itemList;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $expirationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isChecked;

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

    public function getItemList(): ?ItemList
    {
        return $this->itemList;
    }

    public function setItemList(?ItemList $itemList): self
    {
        $this->itemList = $itemList;

        return $this;
    }

    public function getExpirationDate(): ?\DateTimeInterface
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(?\DateTimeInterface $expirationDate): self
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
        ];
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
}
