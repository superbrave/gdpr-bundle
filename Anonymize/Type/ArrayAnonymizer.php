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
 * Class ArrayAnonymizer
 *
 * @package SuperBrave\GdprBundle\Anonymize\Type
 */
class ArrayAnonymizer implements AnonymizerInterface
{
    /**
     * @var AnonymizerInterface
     */
    private $anonymizer;

    /**
     * ArrayAnonymizer constructor.
     *
     * @param AnonymizerInterface $anonymizer Any Anonymizer that complies to it's interface
     */
    public function __construct(AnonymizerInterface $anonymizer)
    {
        $this->anonymizer = $anonymizer;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException if property is not iterable.
     */
    public function anonymize($propertyValue, array $options = [])
    {
        if (!is_iterable($propertyValue)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid argument given; "%s" for class "%s" should be of type iterable.',
                gettype($propertyValue),
                __CLASS__
            ));
        }

        foreach ($propertyValue as $index => $value) {
            $propertyValue[$index] = $this->anonymizer->anonymize($value, $options);
        }

        return $propertyValue;
    }
}
