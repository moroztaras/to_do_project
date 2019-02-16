<?php

namespace App\Normalizer;

use App\Entity\Item;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ItemNormalizer implements NormalizerInterface
{
    const GROUP_DETAILS = 'Item details';

    /**
     * @param Item  $item
     * @param null  $format
     * @param array $context
     *
     * @return array|bool|float|int|string
     */
    public function normalize($item, $format = null, array $context = [])
    {
        $data = [
            'id' => $item->getId(),
            'title' => $item->getTitle(),
            'isChecked' => $item->getIsChecked(),
        ];

        if (isset($context[AbstractNormalizer::GROUPS]) && in_array($this::GROUP_DETAILS, $context[AbstractNormalizer::GROUPS])) {
            if (!empty($item->getExpirationDate())) {
                $data['expirationDate'] = $item->getExpirationDate()->format('H:i d-m-Y');
            }
            if (!empty($item->getAttachment())) {
                $data['attachment'] = $item->getAttachment();
            }
        }

        return $data;
    }

    public function supportsNormalization($item, $format = null)
    {
        return $item instanceof Item;
    }
}
