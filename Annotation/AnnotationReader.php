<?php

namespace SuperBrave\GdprBundle\Annotation;

use Doctrine\Common\Annotations\AnnotationReader as DoctrineAnnotationReader;
use ReflectionClass;

/**
 * AnnotationReader.
 *
 * @author Niels Nijens <nn@superbrave.nl>
 */
class AnnotationReader extends DoctrineAnnotationReader
{
    /**
     * Returns a list of annotation instances for the properties having the specified annotation.
     *
     * @param ReflectionClass $class
     * @param string          $annotationName The FQCN of the annotation class
     *
     * @return object[]
     */
    public function getPropertiesWithAnnotation(ReflectionClass $class, $annotationName)
    {
        $annotatedProperties = array();

        $properties = $class->getProperties();
        foreach ($properties as $property) {
            $annotation = $this->getPropertyAnnotation($property, $annotationName);
            if ($annotation instanceof $annotationName) {
                $annotatedProperties[$property->getName()] = $annotation;
            }
        }

        return $annotatedProperties;
    }
}
