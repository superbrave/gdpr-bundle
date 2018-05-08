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

namespace SuperBrave\GdprBundle\Anonymize\Type;

/**
 * Interface AnonymizerInterface
 *
 * @package SuperBrave\GdprBundle\Anonymize\Type
 */
interface AnonymizerInterface
{
    /**
     * Anonymizes the given property value.
     *
     * @param mixed $propertyValue The value of the property.
     * @param array $options       The options for the anonymizer.
     *
     * @return mixed
     */
    public function anonymize($propertyValue, array $options = []);
}
