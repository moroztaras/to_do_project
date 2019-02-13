<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LabelRepository")
 */
class Label
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
     *     message = "Label title should not be blank"
     * )
     * @Assert\NotBlank(
     *     message = "Label title should not be blank"
     * )
     * @Assert\Length(
     *      min = 5,
     *      max = 20,
     *      minMessage = "Label title must be at least {{ limit }} characters long",
     *      maxMessage = "Label title cannot be longer than {{ limit }} characters"
     * )
     */
    private $title;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ItemList", inversedBy="labels")
     */
    private $itemLists;

    /**
     * Label constructor.
     */
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
        }

        return $this;
    }

    public function removeItemList(ItemList $itemList): self
    {
        if ($this->itemLists->contains($itemList)) {
            $this->itemLists->removeElement($itemList);
        }

        return $this;
    }

    /**
     * @return Label
     */
    public function setItemListsEmpty(): self
    {
        $this->itemLists = new ArrayCollection();

        return $this;
    }

    public function __toString()
    {
        return $this->getTitle();
    }
}
