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

namespace SuperBrave\GdprBundle\Tests\Export;

use InvalidArgumentException;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use SuperBrave\GdprBundle\Export\Exporter;
use SuperBrave\GdprBundle\Tests\AnnotatedMock;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * ExporterTest.
 *
 * @author Niels Nijens <nn@superbrave.nl>
 */
class ExporterTest extends PHPUnit_Framework_TestCase
{
    /**
     * The Exporter instance being tested.
     *
     * @var Exporter
     */
    private $exporter;

    /**
     * The mock serializer instance.
     *
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $serializerMock;

    /**
     * Creates a new Exporter instance for testing.
     *
     * @return void
     */
    public function setUp()
    {
        $this->serializerMock = $this->getMockBuilder(SerializerInterface::class)
            ->getMock();

        $this->exporter = new Exporter($this->serializerMock, 'xml');
    }

    /**
     * Tests if constructing a new Exporter instance sets the instance properties.
     *
     * @return void
     */
    public function testConstruct()
    {
        $this->assertAttributeSame($this->serializerMock, 'serializer', $this->exporter);
        $this->assertAttributeSame('xml', 'format', $this->exporter);
    }

    /**
     * Tests if Exporter::exportObject calls the serializer instance with the specified format
     * and returns the result of the serializer.
     *
     * @return void
     */
    public function testExportObject()
    {
        $annotatedMock = new AnnotatedMock();

        $this->serializerMock->expects($this->once())
            ->method('serialize')
            ->with(
                $annotatedMock,
                'xml',
                array('xml_root_node_name' => 'AnnotatedMock')
            )
            ->willReturn('<?xml version="1.0"?><AnnotatedMock/>');

        $this->assertSame(
            '<?xml version="1.0"?><AnnotatedMock/>',
            $this->exporter->exportObject($annotatedMock)
        );
    }

    /**
     * Tests if Exporter::exportObject calls the serializer instance with the custom object name as context
     * and returns the result of the serializer.
     *
     * @return void
     */
    public function testExportObjectWithObjectName()
    {
        $annotatedMock = new AnnotatedMock();

        $this->serializerMock->expects($this->once())
            ->method('serialize')
            ->with(
                $annotatedMock,
                'xml',
                array('xml_root_node_name' => 'custom_name')
            )
            ->willReturn('<?xml version="1.0"?><custom_name/>');

        $this->assertSame(
            '<?xml version="1.0"?><custom_name/>',
            $this->exporter->exportObject($annotatedMock, 'custom_name')
        );
    }

    /**
     * Tests if Exporter::exportObject throws an InvalidArgumentException when the first argument is not an object.
     *
     * @return void
     */
    public function testExportObjectThrowsInvalidArgumentExceptionWhenObjectIsNotObject()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('$object must be of type object. string given.');

        $this->exporter->exportObject('no object');
    }
}
