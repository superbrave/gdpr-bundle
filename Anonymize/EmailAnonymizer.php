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
 * Email anonymizer class
 *
 * @package SuperBrave\GdprBundle\Anonymize
 */
class EmailAnonymizer implements AnonymizerInterface
{
    /**
     * @param mixed $propertyValue  The value that has to be converted
     * @param array $options        Options to help the anonymizer do its job
     *
     * @return string               The Anonymized string
     */
    public function anonymize($propertyValue, array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $options = $resolver->resolve($options);

        return sprintf('%s-%u@localhost', $options['fieldName'], $options['id']);
    }

    private function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(['fieldName', 'id'])
            ->setAllowedTypes('id', 'int')
            ->setAllowedTypes('fieldName', 'string')
        ;
    }
}
