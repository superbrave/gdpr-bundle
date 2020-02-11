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
 * @see       https://www.superbrave.nl/
 */

namespace Superbrave\GdprBundle\Anonymize\Type;

use Superbrave\GdprBundle\Anonymize\Anonymizer;

/**
 * Class ObjectAnonymizer.
 */
class ObjectAnonymizer implements AnonymizerInterface
{
    /**
     * @var Anonymizer
     */
    private $anonymizer;

    /**
     * ObjectAnonymizer constructor.
     *
     * @param Anonymizer $anonymizer the anonymizer
     */
    public function __construct(Anonymizer $anonymizer)
    {
        $this->anonymizer = $anonymizer;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException if propertyValue is not an object
     * @throws \ReflectionException      if class does't exist
     */
    public function anonymize($propertyValue, array $options = [])
    {
        if (!is_object($propertyValue)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid argument given; "%s" for class "%s" should be of type object.',
                gettype($propertyValue),
                __CLASS__
            ));
        }

        $this->anonymizer->anonymize($propertyValue);

        return $propertyValue;
    }
}
