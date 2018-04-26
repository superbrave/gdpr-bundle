<?php

namespace SuperBrave\GdprBundle\Anonymize\Type;

use SuperBrave\GdprBundle\Anonymize\Anonymizer;
use SuperBrave\GdprBundle\Anonymize\AnonymizerInterface;

class ArrayAnonymizer implements AnonymizerInterface
{
    private $anonymizer;

    /**
     * ArrayAnonymizer constructor.
     *
     * @param Anonymizer $anonymizer
     */
    public function __construct(Anonymizer $anonymizer)
    {
        $this->anonymizer = $anonymizer;
    }

    /**
     * {@inheritdoc}
     */
    public function anonymize($propertyValue, array $options = [])
    {
        foreach ($propertyValue as &$value) {
            $this->anonymizer->anonymize($value);
        }
    }
}