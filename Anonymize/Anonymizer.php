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
 * @package SuperBrave\GdprBundle\Anonymize
 */
class Anonymizer
{
    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * Anonymizer constructor.
     *
     * @param AnnotationReader $annotationReader
     */
    public function __construct(AnnotationReader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    /**
     * @param ObjectAnonymizeInterface $object
     *
     * @todo implement function
     */
    public function anonymize(ObjectAnonymizeInterface $object)
    {
    }
}
