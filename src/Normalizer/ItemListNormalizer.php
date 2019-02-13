<?php

namespace App\Normalizer;

use App\Entity\ItemList;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

class ItemListNormalizer implements NormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    const GROUP_DETAILS = 'List details';

    /**
     * @param ItemList $itemList
     * @param null     $format
     * @param array    $context
     *
     * @return array|bool|float|int|string
     */
    public function normalize($itemList, $format = null, array $context = [])
    {
        $data = [
            'id' => $itemList->getId(),
            'title' => $itemList->getTitle(),
        ];

        if (count($itemList->getLabels())) {
            $data['labels'] = $this->serializer->normalize($itemList->getLabels(), $format, $context);
        }

        if (isset($context[AbstractNormalizer::GROUPS]) && in_array($this::GROUP_DETAILS, $context[AbstractNormalizer::GROUPS])) {
            if (count($itemList->getItems())) {
                $data['items'] = $this->serializer->normalize($itemList->getItems(), $format, $context);
            }
        }

        return $data;
    }

    public function supportsNormalization($itemList, $format = null)
    {
        return $itemList instanceof ItemList;
    }
}
