<?php
/**
 * This file is part of the GDPR bundle.
 *
 * @category  Bundle
 *
 * @author    SuperBrave <info@superbrave.nl>
 * @copyright 2018 SuperBrave <info@superbrave.nl>
 * @license   https://github.com/superbrave/gdpr-bundle/blob/master/LICENSE MIT
 *
 * @see      https://www.superbrave.nl/
 */

namespace Superbrave\GdprBundle\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Normalizes data if it's iterable by calling the normalizer chain.
 *
 * @author Jelle van Oosterbosch <jvo@superbrave.nl>
 */
class IterableNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        if (is_array($data) || $data instanceof \Traversable) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $normalizedData = [];

        foreach ($object as $value) {
            $normalizedData[] = $this->normalizer->normalize($value, $format, $context);
        }

        return $normalizedData;
    }
}
