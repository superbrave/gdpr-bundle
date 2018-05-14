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

namespace SuperBrave\GdprBundle\Tests;

use SuperBrave\GdprBundle\Annotation as GDPR;

/**
 * Class used to test the @see GDPR\AnnotationReader.
 *
 * @author Niels Nijens <nn@superbrave.nl>
 */
class ExtendedAnnotedMock extends AnnotatedMock
{
    /**
     * The extended property.
     *
     * @GDPR\Export()
     *
     * @var bool
     */
    private $extendedProperty = true;

    /**
     * Returns the extended property.
     *
     * @return bool
     */
    public function getExtendedProperty()
    {
        return $this->extendedProperty;
    }
}
