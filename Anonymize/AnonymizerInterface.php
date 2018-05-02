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

/**
 * Interface AnonymizerInterface
 *
 * @package SuperBrave\GdprBundle\Anonymize
 */
interface AnonymizerInterface
{
    /**
     * Anonymize the data given the value and options
     *
     * @param mixed $propertyValue The value used to anonymize
     * @param array $options       Options that may be used to help anonymization
     *
     * @return mixed
     */
    public function anonymize($propertyValue, array $options = []);
}