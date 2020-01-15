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

namespace Superbrave\GdprBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Annotation to flag an entity field to be exported
 * to conform with the GDPR right of portability.
 *
 * @Annotation
 * @Annotation\Target({"PROPERTY"})
 *
 * @author Niels Nijens <nn@superbrave.nl>
 */
class Export
{
    /**
     * The alias is used as name/key for the exported value instead of property name.
     *
     * @var string
     */
    public $alias;

    /**
     * The value map used to change a value to a human-readable value.
     *
     * @var array
     */
    public $valueMap;
}
