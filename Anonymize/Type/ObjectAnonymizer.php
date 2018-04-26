<?php

namespace SuperBrave\GdprBundle\Anonymize\Type;

use SuperBrave\GdprBundle\Anonymize\Anonymizer;
use SuperBrave\GdprBundle\Anonymize\AnonymizerInterface;

class ObjectAnonymizer implements AnonymizerInterface
{
    private $anonymizer;

    /**
     * ObjectAnonymizer constructor.
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
        $this->anonymizer->anonymize($propertyValue);
    }
}