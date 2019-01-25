<?php

namespace App\Listener;

use App\Entity\Item;
use Doctrine\ORM\Mapping\PreFlush;

class ItemListener
{
    /** @PreFlush */
    public function preFlushHandler(Item $item)
    {
        if ($item->getIsChecked() == null)
            $item->setIsChecked(false);
    }
}
