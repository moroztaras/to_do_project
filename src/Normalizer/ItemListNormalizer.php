<?php

namespace App\Normalizer;

use App\Entity\Item;
use App\Entity\ItemList;
use App\Entity\Label;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ItemListNormalizer implements NormalizerInterface
{
    const FORMAT_DETAILED = 'detailed';

    /**
     * @param mixed $itemList
     * @param null  $format
     * @param array $context
     *
     * @return array|bool|float|int|string
     */
    public function normalize($itemList, $format = null, array $context = [])
    {
        if (self::FORMAT_DETAILED == $format) {
            return [
                'id' => $itemList->getId(),
                'name' => $itemList->getName(),
                'labels' => array_map(
                    function (Label $label) {
                        return [
                            'title' => $label->getTitle(),
                        ];
                    },
                    $itemList->getLabels()->toArray()),
                'items' => array_map(
                    function (Item $item) {
                        return [
                            'id' => $item->getId(),
                            'title' => $item->getTitle(),
                            'expiration' => $item->getExpiration(),
                            'isChecked' => $item->getIsChecked(),
                        ];
                    },
                    $itemList->getItems()->toArray()),
            ];
        }

        return [
            'id' => $itemList->getId(),
            'name' => $itemList->getName(),
            'labels' => array_map(
                function (Label $label) {
                    return $label->getTitle();
                },
                $itemList->getLabels()->toArray()),
        ];
    }

    public function supportsNormalization($itemList, $format = null)
    {
        return $itemList instanceof ItemList;
    }
}
