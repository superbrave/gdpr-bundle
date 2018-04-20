<?php

namespace SuperBrave\GdprBundle\Anonymizer;

use LogicException;

/**
 *
 *
 * @package SuperBrave\GdprBundle\Anonymizer
 */
class AnonymizerCollection
{
    /**
     * @var AnonymizerInterface[]
     */
    private $anonymizers = array();

    /**
     * @param string $type
     * @param AnonymizerInterface $anonymizer
     */
    public function addAnonymizer($type, $anonymizer)
    {
        if (array_key_exists($type, $this->anonymizers)) {
            throw new LogicException(sprintf('Anonymizer %s already exists.', $type));
        }

        $this->anonymizers[$type] = $anonymizer;
    }

    /**
     * @param string $type
     * @return AnonymizerInterface
     */
    public function getAnonymizer($type)
    {
        if (!array_key_exists($type, $this->anonymizers)) {
            throw new LogicException(sprintf('Anonymizer %s is not registered.', $type));
        }

        return $this->anonymizers[$type];
    }
}
