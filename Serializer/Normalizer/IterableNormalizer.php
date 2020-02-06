<?php

namespace Superbrave\GdprBundle\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Normalizes data if its iterable by calling the normalizer chain.
 *
 * @author Jelle van Oosterbosch <jvo@superbrave.nl>
 */
class IterableNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * {@inheritDoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        if (true === is_object($data) && true === is_iterable($data)) {
            return true;
        }

        if (true === is_array($data)) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {
        $normalizedData = [];

        foreach ($object as $value) {
            $normalizedData[] = $this->normalizer->normalize($value, $format, $context);
        }

        return $normalizedData;
    }
}
