<?php
/**
 * This file is part of the GDPR bundle.
 *
 * @category  Bundle
 *
 * @author    SuperBrave <info@superbrave.nl>
 * @copyright 2018 SuperBrave <info@superbrave.nl>
 * @license   https://github.com/superbrave/gdpr-bundle/blob/master/LICENSE MIT
 *
 * @see       https://www.superbrave.nl/
 */

namespace Superbrave\GdprBundle\Anonymize;

use LogicException;
use Superbrave\GdprBundle\Anonymize\Type\AnonymizerInterface;

/**
 * Class AnonymizerCollection.
 */
class AnonymizerCollection
{
    /**
     * Array of anonymizers.
     *
     * @var AnonymizerInterface[]
     */
    private $anonymizers = [];

    /**
     * Adds an anonymizer to the collection.
     *
     * @param string              $type       the anonymizer type to be added
     * @param AnonymizerInterface $anonymizer the anonymizer class to be added
     *
     * @return void
     *
     * @throws LogicException on duplicate anonymizer keys
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
     * @param string $type the anonymizer type to be fetched
     *
     * @return AnonymizerInterface
     *
     * @throws LogicException if the anonymizer type is not registered
     */
    public function getAnonymizer($type)
    {
        if (!array_key_exists($type, $this->anonymizers)) {
            throw new LogicException(sprintf('Anonymizer %s is not registered.', $type));
        }

        return $this->anonymizers[$type];
    }
}
