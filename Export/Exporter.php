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

namespace Superbrave\GdprBundle\Export;

use InvalidArgumentException;
use ReflectionException;
use ReflectionClass;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Exporter.
 *
 * @author Niels Nijens <nn@superbrave.nl>
 */
class Exporter
{
    /**
     * The Serializer instance.
     *
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * Constructs a new Export instance.
     *
     * @param SerializerInterface $serializer The Serializer instance
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Returns a serialized/exported version of the specified object.
     *
     * @param object      $object         The object to be exported
     * @param string|null $objectName     The name of the object used in the export (eg. the root node in XML)
     * @param string      $format         The format an object is serialized to
     * @param string|null $targetEncoding The target encoding for the export (XML)
     *
     * @return string
     *
     * @throws InvalidArgumentException
     * @throws ReflectionException
     */
    public function exportObject(/*object */$object, $objectName = null, $format = 'xml', $targetEncoding = null)
    {
        if (is_object($object) === false) {
            throw new InvalidArgumentException(
                sprintf('$object must be of type object. %s given.', gettype($object))
            );
        }

        $context = array(
            'xml_root_node_name' => $objectName,
        );
        if ($targetEncoding !== null) {
            $context['xml_encoding'] = $targetEncoding;
        }

        if (is_string($context['xml_root_node_name']) === false) {
            $context['xml_root_node_name'] = (new ReflectionClass($object))->getShortName();
        }

        return $this->serializer->serialize($object, $format, $context);
    }
}
