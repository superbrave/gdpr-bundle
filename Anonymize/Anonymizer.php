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
 * @see       https://www.superbrave.nl/
 */

namespace Superbrave\GdprBundle\Anonymize;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use Superbrave\GdprBundle\Annotation\AnnotationReader;
use Superbrave\GdprBundle\Annotation\Anonymize;

/**
 * Class Anonymizer.
 */
class Anonymizer
{
    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * @var PropertyAnonymizer
     */
    private $propertyAnonymizer;

    /**
     * Anonymizer constructor.
     *
     * @param AnnotationReader   $annotationReader   the annotation reader that should be used
     * @param PropertyAnonymizer $propertyAnonymizer the property anonymizer
     */
    public function __construct(
        AnnotationReader $annotationReader,
        PropertyAnonymizer $propertyAnonymizer
    ) {
        $this->annotationReader = $annotationReader;
        $this->propertyAnonymizer = $propertyAnonymizer;
    }

    /**
     * Anonymizes the given object which should contain the @see Anonymize annotations.
     *
     * @param object $object the object to anonymize
     *
     * @return void
     *
     * @throws InvalidArgumentException if argument supplied is not an object
     * @throws ReflectionException      if class doesn't exist
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
        $annotations = $this->annotationReader->getPropertiesWithAnnotation($reflectionClass, Anonymize::class);

        foreach ($annotations as $property => $annotation) {
            $this->propertyAnonymizer->anonymizeField($object, $property, $annotation);
        }
    }
}
