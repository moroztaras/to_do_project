<?php

namespace App\Normalizer;

use App\Entity\Label;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class LabelNormalizer implements NormalizerInterface
{
    /**
     * @param Label $label
     * @param null  $format
     * @param array $context
     *
     * @return array|bool|float|int|string
     */
    public function normalize($label, $format = null, array $context = [])
    {
        return [
            'id' => $label->getId(),
            'title' => $label->getTitle(),
        ];
    }

    public function supportsNormalization($label, $format = null)
    {
        return $label instanceof Label;
    }
}
