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

use SuperBrave\GdprBundle\Anonymize\Anonymizer;

/**
 * Class ObjectAnonymizer
 *
 * @package SuperBrave\GdprBundle\Anonymize\Type
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
     * @param Anonymizer $anonymizer Any Anonymizer
     */
    public function __construct(Anonymizer $anonymizer)
    {
        $this->anonymizer = $anonymizer;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException if propertyValue is not an object.
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
