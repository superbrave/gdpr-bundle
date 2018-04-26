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

use SuperBrave\GdprBundle\Annotation\AnnotationReader;
use SuperBrave\GdprBundle\Annotation\Anonymize;
use SuperBrave\GdprBundle\Manipulator\PropertyManipulator;
use InvalidArgumentException;
use ReflectionException;
use ReflectionClass;

/**
 * Class Anonymizer
 * @package SuperBrave\GdprBundle\Anonymize
 */
class Anonymizer
{
    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * @var AnonymizerCollection
     */
    private $anonymizerCollection;

    /**
     * @var PropertyManipulator
     */
    private $propertyManipulator;

    /**
     * Anonymizer constructor.
     *
     * @param AnnotationReader     $annotationReader
     * @param AnonymizerCollection $anonymizerCollection
     * @param PropertyManipulator  $propertyManipulator
     */
    public function __construct(
        AnnotationReader $annotationReader,
        AnonymizerCollection $anonymizerCollection,
        PropertyManipulator $propertyManipulator
    ) {
        $this->annotationReader     = $annotationReader;
        $this->anonymizerCollection = $anonymizerCollection;
        $this->propertyManipulator  = $propertyManipulator;
    }

    /**
     * Anonymizes the given object which should contain the @see Anonymize annotations.
     *
     * @param object $object The object to anonymize.
     *
     * @throws InvalidArgumentException
     * @throws ReflectionException
     */
    public function anonymize(/*object */$object)
    {
        if (!is_object($object)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid argument given "%s" should be of type object.',
                gettype($object)
            ));
        }

        $reflectionClass = new ReflectionClass($object);
        $annotations     = $this->annotationReader->getPropertiesWithAnnotation($reflectionClass, Anonymize::class);

        foreach ($annotations as $property => $annotation) {
            $this->anonymizeField($object, $property, $annotation);
        }
    }

    /**
     * Anonymizes the given property on the given object by the given annotation.
     * The value is used for recursion in case the given property is an object or array.
     *
     * @param object     $object The current object to be anonymized.
     * @param string     $property The field property of the annotation.
     * @param Anonymize  $annotation The annotation of the field.
     * @param null|mixed $value The recursive value if used by traversal.
     *
     * @return void
     *
     * @throws ReflectionException
     */
    private function anonymizeField($object, $property, Anonymize $annotation, &$value = null)
    {
        if (null === $value) {
            $value = $this->propertyManipulator->getPropertyValue($object, $property);
        }

        if (is_object($value)) {
            $this->anonymize($value);
            return;
        }

        if (is_array($value)) {
            foreach ($value as &$item) {
                $this->anonymizeField($object, $property, $annotation, $item);
            }

            $this->propertyManipulator->setPropertyValue($object, $property, $value);
            return;
        }

        $anonymizer = $this->anonymizerCollection->getAnonymizer($annotation->type);
        $value = $anonymizer->anonymize($value, array(
            'annotationValue' => $annotation->value,
            'object' => $object,
        ));
        $this->propertyManipulator->setPropertyValue($object, $property, $value);
    }
}
