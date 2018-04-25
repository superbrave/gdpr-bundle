<?php
/**
 * This file contains the Anonymize annotation class
 *
 * Minimal required PHP version is 5.6
 *
 * @category  Bundle
 * @package   Gdpr
 * @author    Superbrave <info@superbrave.nl>
 * @copyright 2018 Superbrave <info@superbrave.nl>
 * @license   https://github.com/superbrave/gdpr-bundle/blob/master/LICENSE MIT
 * @link      https://www.superbrave.nl/
 */

namespace SuperBrave\GdprBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;

/**
 * Annotation to flag an entity field to be anonymized
 * to conform with the GDPR right to be forgotten
 *
 * @Annotation()
 * @Annotation\Target({"PROPERTY"})
 *
 * @Attributes({
 *    @Attribute("type",  required=false, type="string"),
 *    @Attribute("value", required=false, type="string")
 * })
 */
class Anonymize
{
    /**
     * The type used to specify what kind of anonymizer should be used for the field
     *
     * @var string
     */
    public $type;

    /**
     * The value used as a hard string to be used to anonymize the field
     *
     * @var string
     */
    public $value;
}
