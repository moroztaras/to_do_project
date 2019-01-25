<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LabelRepository")
 */
class Label implements \JsonSerializable
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
     *      minMessage = "Label title must be at least {{ limit }} characters long",
     *      maxMessage = "Label title cannot be longer than {{ limit }} characters"
     * )
     * @Assert\NotNull(
     *     message = "Label title should not be blank"
     * )
     */
    private $title;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ItemList", mappedBy="labels")
     */
    private $itemLists;

    public function __construct()
    {
        $this->itemLists = new ArrayCollection();
    }

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

    /**
     * @return Collection|ItemList[]
     */
    public function getItemLists(): Collection
    {
        return $this->itemLists;
    }

    public function addItemList(ItemList $itemList): self
    {
        if (!$this->itemLists->contains($itemList)) {
            $this->itemLists[] = $itemList;
            $itemList->addLabel($this);
        }

        return $this;
    }

    public function removeItemList(ItemList $itemList): self
    {
        if ($this->itemLists->contains($itemList)) {
            $this->itemLists->removeElement($itemList);
            $itemList->removeLabel($this);
        }

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'title' => $this->getTitle()
        ];
    }
}
