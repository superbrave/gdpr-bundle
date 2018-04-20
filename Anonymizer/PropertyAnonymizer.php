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

    public function anonymizeField($object, ReflectionProperty $property, Anonymize $annotation)
    {
        $anonymizer = $this->anonymizerCollection->getAnonymizer($annotation->type);

        $property->setAccessible(true);

        $propertyValue = $property->getValue($object);

        $newPropertyValue = $anonymizer->anonymize($propertyValue, array(
            'annotationValue' => $annotation->value
        ));

        $property->setValue($object, $newPropertyValue);
    }
}
