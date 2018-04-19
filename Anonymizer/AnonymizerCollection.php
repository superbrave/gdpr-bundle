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

    public function __construct($strategies)
    {
        foreach ($strategies as $strategy) {
            var_dump($strategy);
        }
    }

    /**
     * @param string $type
     * @param AnonymizerInterface $anonymizer
     */
    public function addAnonymizer($type, $anonymizer)
    {
        if (array_key_exists($type, $this->anonymizers)) {
            throw new LogicException(sprintf('You moron! %s already exists!', $type));
        }
        $this->anonymizers[$type] = $anonymizer;
    }

    /**
     * @param string $type
     * @return AnonymizerInterface
     */
    public function getAnonymizer($type)
    {
        return $this->anonymizers[$type];
    }
}
