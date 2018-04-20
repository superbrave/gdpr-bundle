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

namespace SuperBrave\GdprBundle\Anonymizer;

/**
 * Class FixedValueAnonymizer
 *
 * @package SuperBrave\GdprBundle\Anonymizer
 */
class FixedValueAnonymizer implements AnonymizerInterface
{
    public function anonymize($propertyValue, array $options = [])
    {
        return $options['annotationValue'];
    }
}
