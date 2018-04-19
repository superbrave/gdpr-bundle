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
use Doctrine\Common\Annotations\AnnotationException;

/**
 * Annotation to flag an entity field to be anonymized
 * to conform with the GDPR right to be forgotten
 *
 * @Annotation()
 * @Annotation\Target({"PROPERTY"})
 *
 * @Attributes({
 *    @Attribute("type",  required=true,  type="string"),
 *    @Attribute("value", required=false, type="string")
 * })
 */
class Anonymize
{
    /**
     * Alias of TYPE_CONCAT
     */
    const TYPE_STRING = 'string';

    /**
     * Anonymize a string to <columnName>-<primaryKey>
     */
    const TYPE_CONCAT = 'concat';

    /**
     * Anonymize a field by emptying it
     * Alias of TYPE_FIXED with an empty value.
     */
    const TYPE_TRUNCATE = 'truncate';

    /**
     * Anonymize an IP field by setting the last bytes to 0
     *
     * @see https://support.google.com/analytics/answer/2763052
     *
     * Supports string fields only
     */
    const TYPE_IP = 'ip';

    /**
     * Anonymize by providing a fixed value
     * Requires the value property to be given
     */
    const TYPE_FIXED = 'fixed';

    /**
     * Anonymize an email to "<primaryKey>@localhost"
     */
    const TYPE_EMAIL = 'email';

    /**
     * The type used to specify what kind of anonymizer should be used for the field
     *
     * @Annotation\Enum({
     *     Anonymize::TYPE_STRING,
     *     Anonymize::TYPE_IP,
     *     Anonymize::TYPE_FIXED,
     *     Anonymize::TYPE_EMAIL,
     *     Anonymize::TYPE_CONCAT,
     *     Anonymize::TYPE_TRUNCATE
     * })
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

    /**
     * Performs a validation check according to the type given.
     * If this type is 'Fixed' then a value has to be passed in as second argument
     *
     * @param array $arguments Array of arguments passed into the annotation
     *
     * @throws \Exception
     * @throws AnnotationException
     */
    public function __construct(array $arguments)
    {
        $this->type = $arguments['type'];
        $this->value = $arguments['value'];

        if (self::TYPE_FIXED === $this->type && null === $this->value) {
            throw new AnnotationException("'fixed' type requires 'value' property to be set.");
        }
    }
}
