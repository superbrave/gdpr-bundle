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

use SuperBrave\GdprBundle\Manipulator\PropertyManipulator;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Email anonymizer class
 *
 * @package SuperBrave\GdprBundle\Anonymize\Type
 */
class FixedValueAnonymizer implements AnonymizerInterface
{
    /**
     * Property Manipulator service
     *
     * @var PropertyManipulator
     */
    private $propertyManipulator;

    /**
     * StringAnonymizer constructor.
     * @param PropertyManipulator $propertyManipulator
     */
    public function __construct(PropertyManipulator $propertyManipulator)
    {
        $this->propertyManipulator = $propertyManipulator;
    }

    /**
     * @param mixed $propertyValue The value that has to be converted
     * @param array $options Options to help the anonymizer do its job
     *
     * @return string               The Anonymized string
     */
    public function anonymize($propertyValue, array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $options = $resolver->resolve($options);

        $annotationValue = $options['annotationValue'];

        $matches = [];
        if (preg_match_all('/{(.*?)}/', $options['annotationValue'], $matches)) {
            foreach ((array)$matches[1] as $key => $property) {
                $wildcardValue = $this->propertyManipulator->getPropertyValue($options['object'], $property);
                $annotationValue = str_replace($matches[0][$key], $wildcardValue, $annotationValue);
            }
        }

        return $annotationValue;
    }

    private function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['annotationValue', 'object']);
    }
}
