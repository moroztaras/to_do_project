<?php

namespace App\Listener;

use App\Entity\Item;
use Doctrine\ORM\Mapping\PreFlush;

class ItemEntityListener
{
    /** @PreFlush */
    public function preFlushHandler(Item $item)
    {
        if (null == $item->getIsChecked()) {
            $item->setIsChecked(false);
        }
    }
}
