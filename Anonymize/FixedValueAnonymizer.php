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

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FixedValueAnonymizer
 *
 * @package SuperBrave\GdprBundle\Anonymize
 */
class FixedValueAnonymizer implements AnonymizerInterface
{
    public function anonymize($propertyValue, array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $options = $resolver->resolve($options);

        return $options['annotationValue'];
    }

    private function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['annotationValue']);
    }
}
