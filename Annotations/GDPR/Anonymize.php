<?php

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\AnnotationException;

/**
 * Class Anonymize
 *
 * @Annotation()
 * @Annotation\Target({"PROPERTY"})
 */
class Anonymize
{
    const TYPE_STRING = 'string';
    const TYPE_IP = 'ip';
    const TYPE_FIXED = 'fixed';
    const TYPE_EMAIL = 'email';

    /**
     * @Annotation\Enum({
     *     Anonymize::TYPE_STRING
     *     Anonymize::TYPE_IP
     *     Anonymize::TYPE_FIXED
     *     Anonymize::TYPE_EMAIL
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

// @GDPR\Anonymize(type='fixed', value='127.0.0.1')
