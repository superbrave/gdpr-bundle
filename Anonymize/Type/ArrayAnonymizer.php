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
 * Class ArrayAnonymizer.
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
     * @param AnonymizerInterface $anonymizer the anonymizer
     */
    public function __construct(AnonymizerInterface $anonymizer)
    {
        $this->anonymizer = $anonymizer;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException if property is not iterable
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
