<?php

namespace App\Normalizer;

use App\Entity\Label;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class LabelNormalizer implements NormalizerInterface
{
    /**
     * @param mixed $itemList
     * @param null  $format
     * @param array $context
     *
     * @return array|bool|float|int|string
     */
    public function normalize($itemList, $format = null, array $context = [])
    {
        return [
            'name' => $itemList->getName(),
        ];
    }

    public function supportsNormalization($itemList, $format = null)
    {
        return $itemList instanceof Label;
    }
}
