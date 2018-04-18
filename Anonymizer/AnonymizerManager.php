<?php

namespace SuperBrave\GdprBundle\Anonymizer;

use LogicException;

/**
 *
 *
 * @package SuperBrave\GdprBundle\Anonymizer
 */
class AnonymizerManager
{
    /**
     * @var AnonymizerInterface[]
     */
    private $anonymizers = array();

    /**
     * AnonymizerManager constructor.
     *
     * @param array $anonymizers
     */
    public function __construct($anonymizers)
    {
        foreach ($anonymizers as $anonymizer) {
            if (!($anonymizer instanceof AnonymizerInterface)) {
                throw new LogicException();
            }
        }
        $this->anonymizers = $anonymizers;
    }
}
