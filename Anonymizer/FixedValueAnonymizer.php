<?php

namespace SuperBrave\GdprBundle\Anonymizer;

/**
 * Class FixedValueAnonymizer
 *
 * @package SuperBrave\GdprBundle\Anonymizer
 */
class FixedValueAnonymizer implements AnonymizerInterface
{
    public function anonymize($propertyValue, $annotationValue)
    {
        return $annotationValue;
    }
}
