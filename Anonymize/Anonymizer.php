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

/**
 * Class Anonymizer
 *
 * @package SuperBrave\GdprBundle\Anonymize
 */
class Anonymizer
{
    /**
     * The annotation reader service
     *
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * Anonymizer constructor.
     *
     * @param AnnotationReader $annotationReader The annotation reader service
     */
    public function __construct(AnnotationReader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    /**
     * Anonymize the object passed
     *
     * @param object $object The object that is being anonymized
     *
     * @todo implement function
     *
     * @return void
     */
    public function anonymize($object)
    {
        if (!is_object($object)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid argument given "%s" should be of type object.',
                gettype($object)
            ));
        }
    }
}
