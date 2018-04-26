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
use SuperBrave\GdprBundle\Anonymize\Type\AnonymizerInterface;

/**
 * Class AnonymizerCollection
 *
 * @package SuperBrave\GdprBundle\Anonymize
 */
class AnonymizerCollection
{
    /**
     * Array of anonymizers
     *
     * @var AnonymizerInterface[]
     */
    private $anonymizers = [];

    /**
     * Adds an anonymizer to the collection.
     *
     * @param string              $type       The anonymizer type to be added
     * @param AnonymizerInterface $anonymizer The anonymizer class to be added
     *
     * @return void
     */
    public function addAnonymizer($type, $anonymizer)
    {
        if (array_key_exists($type, $this->anonymizers)) {
            throw new LogicException(sprintf('Anonymizer %s already exists.', $type));
        }

        $this->anonymizers[$type] = $anonymizer;
    }

    /**
     * Get an anonymizer by its type from the collection.
     *
     * @param string $type The anonymizer type to be fetched
     *
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
