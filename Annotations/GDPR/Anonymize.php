<?php

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\AnnotationException;

/**
 * Annotation to flag an entity field to be anonymized
 * to conform with the GDPR right to be forgotten
 * 
 * @Annotation()
 * @Annotation\Target({"PROPERTY"})
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
     * Anonymize an IP field by filling in 127.0.0.1
     * If this is a string field, INET_ATON will be used instead.
     */
    const TYPE_IP = 'ip';

    /**
     * Anonymize by providing a fixed value
     * Requires the value property to be given
     */
    const TYPE_FIXED = 'fixed';

    /** Anonymize an email to "<primaryKey>@example.com" */
    const TYPE_EMAIL = 'email';

    /**
     * @Annotation\Enum({
     *     Anonymize::TYPE_STRING
     *     Anonymize::TYPE_IP
     *     Anonymize::TYPE_FIXED
     *     Anonymize::TYPE_EMAIL
     *     Anonymize::TYPE_CONCAT
     *     Anonymize::TYPE_TRUNCATE
     * })
     * @var string
     */
    public $type;

    /** @var string */
    public $value;

    public function __construct(array $values)
    {
        $this->type = $values['type'];
        $this->value = $values['value'];

        if (self::TYPE_FIXED === $this->type && null === $this->value) {
            throw new AnnotationException("'fixed' type requires 'value' property to be set.");
        }
    }
}
