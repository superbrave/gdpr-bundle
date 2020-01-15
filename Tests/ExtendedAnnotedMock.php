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
 * @see      https://www.superbrave.nl/
 */

namespace Superbrave\GdprBundle\Tests;

use Superbrave\GdprBundle\Annotation as GDPR;

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
    public function getExtendedProperty(): bool
    {
        return $this->extendedProperty;
    }
}
