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

namespace Superbrave\GdprBundle\DependencyInjection;

use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * ObjectFactory.
 *
 * @author Niels Nijens <nn@superbrave.nl>
 */
class ObjectFactory
{
    /**
     * Returns a new Serializer instance.
     *
     * @param NormalizerInterface[] $normalizers The collection of tagged normalizer services
     * @param EncoderInterface[]    $encoders    The collection of tagged encoder services
     *
     * @return Serializer
     */
    public static function createSerializer($normalizers, $encoders)
    {
        return new Serializer(
            iterator_to_array($normalizers),
            iterator_to_array($encoders)
        );
    }
}
