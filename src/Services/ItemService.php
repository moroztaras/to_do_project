<?php

namespace App\Services;

use App\Entity\Item;

class ItemService
{
    public function updateItemFromJson(Item $itemToUpdate, Item $itemFromUpdate)
    {
        if (null != $itemFromUpdate->getTitle()) {
            $itemToUpdate->setTitle($itemFromUpdate->getTitle());
        }

        if (null != $itemFromUpdate->getExpiration()) {
            $itemToUpdate->setExpiration($itemFromUpdate->getExpiration());
        }

        if (null != $itemFromUpdate->getIsChecked()) {
            $itemToUpdate->setIsChecked($itemFromUpdate->getIsChecked());
        }

        return $itemToUpdate;
    }
}
