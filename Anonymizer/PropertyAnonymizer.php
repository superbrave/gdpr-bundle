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

namespace SuperBrave\GdprBundle\Anonymizer;

use ReflectionProperty;
use SuperBrave\GdprBundle\Annotation\Anonymize;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Class PropertyAnonymizer
 *
 * @package SuperBrave\GdprBundle\Anonymizer
 */
class PropertyAnonymizer
{
    /**
     * @var AnonymizerCollection
     */
    private $anonymizerCollection;

    public function __construct(AnonymizerCollection $anonymizerCollection)
    {
        $this->anonymizerCollection = $anonymizerCollection;
    }

    /**
     * Anonymize the property the annotation is on.
     * Takes into account the type specified in the annotation
     *
     * @param $object
     * @param ReflectionProperty $property
     * @param Anonymize $annotation
     */
    public function anonymizeField($object, ReflectionProperty $property, Anonymize $annotation)
    {
        $anonymizer = $this->anonymizerCollection->getAnonymizer($annotation->type);

        $property->setAccessible(true);

        $propertyValue = $property->getValue($object);

        $newPropertyValue = $anonymizer->anonymize($propertyValue, array(
            'annotationValue' => $annotation->value,
            'object' => $object,
        ));

        $property->setValue($object, $newPropertyValue);
    }
}
