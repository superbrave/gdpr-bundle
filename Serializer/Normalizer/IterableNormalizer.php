<?php
/**
 * This file is part of the GDPR bundle.
 *
 * @category  Bundle
 * @package   Gdpr
 * @author    SuperBrave <info@superbrave.nl>
 * @copyright 2018 SuperBrave <info@superbrave.nl>
 * @license   https://github.com/superbrave/gdpr-bundle/blob/master/LICENSE MIT
 * @link      https://www.superbrave.nl/
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
        if (true === is_object($data) &&
            (is_array($data) || (is_object($data)) && ($data instanceof \Traversable))
        ) {
            return true;
        }

        if (true === is_array($data)) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
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
