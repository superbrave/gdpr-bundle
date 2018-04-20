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

namespace SuperBrave\GdprBundle\Export;

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
     * The format an object is serialized to.
     *
     * @var string
     */
    private $format;

    /**
     * Constructs a new Export instance.
     *
     * @param SerializerInterface $serializer The Serializer instance
     * @param string              $format     The format an object is serialized to
     */
    public function __construct(SerializerInterface $serializer, $format = 'xml')
    {
        $this->serializer = $serializer;
        $this->format = $format;
    }

    /**
     * Returns a serialized/exported version of the specified object.
     *
     * @param ObjectExportInterface $object     The object to be exported
     * @param string|null           $objectName The name of the object used in the export (eg. the root node in XML)
     *
     * @return string
     */
    public function exportObject(ObjectExportInterface $object, $objectName = null)
    {
        $context = array(
            'xml_root_node_name' => $objectName,
        );

        if (is_string($context['xml_root_node_name']) === false) {
            $context['xml_root_node_name'] = (new ReflectionClass($object))->getShortName();
        }

        return $this->serializer->serialize($object, $this->format, $context);
    }
}
