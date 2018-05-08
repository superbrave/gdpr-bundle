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

use SuperBrave\GdprBundle\Annotation\Anonymize;
use SuperBrave\GdprBundle\Manipulator\PropertyManipulator;

/**
 * Class PropertyAnonymizer
 *
 * @package SuperBrave\GdprBundle\Anonymizer
 */
class PropertyAnonymizer
{
    /**
     * Property manipulator service
     *
     * @var PropertyManipulator
     */
    private $propertyManipulator;

    /**
     * The collection of anonymizers
     *
     * @var AnonymizerCollection
     */
    private $anonymizerCollection;

    /**
     * Constructs the class given the parameters
     *
     * @param PropertyManipulator  $propertyManipulator  The PropertyManipulator class used to get the property value
     * @param AnonymizerCollection $anonymizerCollection A collection of anonymizers registered by the compiler pass
     */
    public function __construct(PropertyManipulator $propertyManipulator, AnonymizerCollection $anonymizerCollection)
    {
        $this->propertyManipulator = $propertyManipulator;
        $this->anonymizerCollection = $anonymizerCollection;
    }

    /**
     * Anonymize the property the annotation is on.
     * Takes into account the type specified in the annotation
     *
     * @param object    $object     The owner of the property being anonymized
     * @param string    $property   The property being anonymized
     * @param Anonymize $annotation The annotation gotten from the object
     *
     * @return void
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
