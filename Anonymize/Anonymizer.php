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
use InvalidArgumentException;
use ReflectionException;

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
     * @var PropertyAnonymizer
     */
    private $propertyAnonymizer;

    /**
     * Anonymizer constructor.
     *
     * @param AnnotationReader   $annotationReader
     * @param PropertyAnonymizer $propertyAnonymizer
     */
    public function __construct(AnnotationReader $annotationReader, PropertyAnonymizer $propertyAnonymizer)
    {
        $this->annotationReader = $annotationReader;
        $this->propertyAnonymizer = $propertyAnonymizer;
    }

    /**
     * @param object $object
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

        $annotations = $this->annotationReader->getPropertiesWithAnnotation(new \ReflectionClass($object), Anonymize::class);

        foreach ($annotations as $field => $annotation) {
            $this->propertyAnonymizer->anonymizeField($object, $field, $annotation);
        }
    }
}
