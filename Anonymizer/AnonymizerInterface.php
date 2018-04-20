<?php

namespace SuperBrave\GdprBundle\Anonymizer;

/**
 * Interface AnonymizerInterface
 *
 * @package SuperBrave\GdprBundle\Anonymizer
 */
interface AnonymizerInterface
{
    /**
     * @param mixed $propertyValue
     * @param mixed $annotationValue
     * @return mixed
     */
    public function anonymize($propertyValue, $annotationValue);
}
