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
 * Class NullAnonymizer.
 */
class NullAnonymizer implements AnonymizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function anonymize($propertyValue, array $options = [])
    {
        return null;
    }
}
