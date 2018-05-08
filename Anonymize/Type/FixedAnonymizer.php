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
 * Fixed anonymizer class
 *
 * @package SuperBrave\GdprBundle\Anonymize\Type
 */
class FixedAnonymizer implements AnonymizerInterface
{
    /**
     * Property Manipulator service
     *
     * @var PropertyManipulator
     */
    private $propertyManipulator;

    /**
     * FixedAnonymizer constructor.
     *
     * @param PropertyManipulator $propertyManipulator Used to get the value of a property
     */
    public function __construct(PropertyManipulator $propertyManipulator)
    {
        $this->propertyManipulator = $propertyManipulator;
    }

    /**
     * Anonymize the data given according to the options provided
     * The value is required in the annotation for this anonymizer
     *
     * You can specify a property you want to be resolved using a wildcard manner
     *
     * example:
     *    'my-fixed-value-{id}' would become 'my-fixed-value-10'
     *    when the id property on the object returns 10
     *
     * @param mixed $propertyValue The value that has to be converted
     * @param array $options       Options to help the anonymizer do its job
     *
     * @return string              The Anonymized string
     *
     * @throws \InvalidArgumentException if annotationValue is empty.
     */
    public function anonymize($propertyValue, array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $options = $resolver->resolve($options);

        if (null === $options['annotationValue']) {
            throw new \InvalidArgumentException('The option "annotationValue" cannot be empty');
        }

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

    /**
     * Configures the options for this anonymizer.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     *
     * @return void
     */
    private function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['annotationValue', 'object']);
    }
}
