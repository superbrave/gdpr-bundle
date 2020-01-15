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

namespace Superbrave\GdprBundle\Anonymize\Type;

/**
 * Interface AnonymizerInterface.
 */
interface AnonymizerInterface
{
    /**
     * Anonymizes the given property value.
     *
     * @param mixed $propertyValue the value of the property
     * @param array $options       the options for the anonymizer
     *
     * @return mixed
     */
    public function anonymize($propertyValue, array $options = []);
}
