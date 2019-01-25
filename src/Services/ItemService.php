<?php

namespace App\Services;


use App\Entity\Item;

class ItemService
{
    public function updateItemFromJson(Item $itemToUpdate, Item $itemFromUpdate)
    {
        if ($itemFromUpdate->getTitle() != null)
            $itemToUpdate->setTitle($itemFromUpdate->getTitle());

        if ($itemFromUpdate->getExpiration() != null)
            $itemToUpdate->setExpiration($itemFromUpdate->getExpiration());

        if ($itemFromUpdate->getIsChecked() != null)
            $itemToUpdate->setIsChecked($itemFromUpdate->getIsChecked());

        return $itemToUpdate;
    }
}
