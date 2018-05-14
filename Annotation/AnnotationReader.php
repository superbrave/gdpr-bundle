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
     * @param ReflectionClass $class          The ReflectionClass of the class from which
     *                                        the class annotations should be read
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

        $parentClass = $class->getParentClass();
        if ($parentClass instanceof ReflectionClass) {
            $annotatedProperties = array_merge(
                $annotatedProperties,
                $this->getPropertiesWithAnnotation($parentClass, $annotationName)
            );
        }

        return $annotatedProperties;
    }
}
