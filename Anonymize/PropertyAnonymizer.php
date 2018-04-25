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

namespace SuperBrave\GdprBundle\Anonymize;

use ReflectionProperty;
use SuperBrave\GdprBundle\Annotation\Anonymize;
use SuperBrave\GdprBundle\Manipulator\PropertyManipulator;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Class PropertyAnonymizer
 *
 * @package SuperBrave\GdprBundle\Anonymizer
 */
class PropertyAnonymizer
{
    /**
     * @var PropertyManipulator
     */
    private $propertyManipulator;

    /**
     * @var AnonymizerCollection
     */
    private $anonymizerCollection;

    public function __construct(PropertyManipulator $propertyManipulator, AnonymizerCollection $anonymizerCollection)
    {
        $this->propertyManipulator = $propertyManipulator;
        $this->anonymizerCollection = $anonymizerCollection;
    }

    /**
     * Anonymize the property the annotation is on.
     * Takes into account the type specified in the annotation
     *
     * @param $object
     * @param string $property
     * @param Anonymize $annotation
     */
    public function anonymizeField($object, $property, Anonymize $annotation)
    {
        $anonymizer = $this->anonymizerCollection->getAnonymizer($annotation->type);

        $propertyValue = $this->propertyManipulator->getPropertyValue($object, $property);

        $newPropertyValue = $anonymizer->anonymize($propertyValue, array(
            'annotationValue' => $annotation->value,
            'object' => $object,
        ));

        $this->propertyManipulator->setPropertyValue($object, $property, $newPropertyValue);
    }
}
