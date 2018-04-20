<?php

namespace SuperBrave\GdprBundle\Anonymizer;

use ReflectionProperty;
use SuperBrave\GdprBundle\Annotation\Anonymize;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class PropertyAnonymizer
{
    /**
     * @var AnonymizerCollection
     */
    private $anonymizerCollection;

    public function __construct(AnonymizerCollection $anonymizerCollection )
    {
        $this->anonymizerCollection = $anonymizerCollection;
    }

    public function anonymizeField($object, ReflectionProperty $property, Anonymize $annotation)
    {
        $anonymizer = $this->anonymizerCollection->getAnonymizer($annotation->type);

        $property->setAccessible(true);

        $propertyValue = $property->getValue($object);

        $newPropertyValue = $anonymizer->anonymize($propertyValue, $annotation->value);

        $property->setValue($object, $newPropertyValue);
    }
}
