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

namespace SuperBrave\GdprBundle\Anonymize;

use LogicException;

/**
 *
 *
 * @package SuperBrave\GdprBundle\Anonymize
 */
class AnonymizerCollection
{
    /**
     * @var AnonymizerInterface[]
     */
    private $anonymizers = [];

    /**
     * @param string              $type
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
