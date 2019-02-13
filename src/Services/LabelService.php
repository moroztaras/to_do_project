<?php

namespace App\Services;

use App\Entity\ItemList;
use App\Entity\Label;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;

class LabelService
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * LabelService constructor.
     *
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * @param Collection $labels
     * @param ItemList   $itemList
     */
    public function initLabels(Collection $labels, ItemList $itemList): void
    {
        $labels = array_unique($labels->toArray());

        $itemList->setLabelsEmpty();

        /** @var Label $label */
        foreach ($labels as $label) {
            $savedLabel = $this->om->getRepository(Label::class)->findOneByTitle($label->getTitle());
            if (isset($savedLabel)) {
                $itemList->addLabel($savedLabel);
            } else {
                $itemList->addLabel($label);
            }
        }
    }

    /**
     * @param Collection $labels
     * @param ItemList   $itemList
     */
    public function syncLabels(Collection $labels, ItemList $itemList): void
    {
        $labels = array_unique($labels->toArray());

        foreach ($itemList->getLabels() as $itemListLabel) {
            if (in_array($itemListLabel->getTitle(), $labels)) {
                if (false !== ($key = array_search($itemListLabel->getTitle(), $labels))) {
                    unset($labels[$key]);
                }
            } else {
                $itemList->removeLabel($itemListLabel);
            }
        }

        /** @var Label $label */
        foreach ($labels as $label) {
            $savedLabel = $this->om->getRepository(Label::class)->findOneByTitle($label->getTitle());
            if (isset($savedLabel)) {
                $itemList->addLabel($savedLabel);
            } else {
                $label->setItemListsEmpty();
                $itemList->addLabel($label);
            }
        }
    }
}
