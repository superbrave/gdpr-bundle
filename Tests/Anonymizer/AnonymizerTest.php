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

namespace Superbrave\GdprBundle\Tests\Anonymizer;

use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use Superbrave\GdprBundle\Annotation\AnnotationReader;
use Superbrave\GdprBundle\Annotation\Anonymize;
use Superbrave\GdprBundle\Anonymize\Anonymizer;
use Superbrave\GdprBundle\Anonymize\PropertyAnonymizer;
use Superbrave\GdprBundle\Tests\AnnotatedMock;

/**
 * Class AnonymizerTest
 *
 * @package Superbrave\GdprBundle\Tests\Anonymizer
 */
class AnonymizerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Anonymizer
     */
    private $anonymizer;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $annotationReaderMock;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $propertyAnonymizer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->annotationReaderMock = $this->getMockBuilder(AnnotationReader::class)
            ->getMock();

        $this->propertyAnonymizer = $this->getMockBuilder(PropertyAnonymizer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->anonymizer = new Anonymizer(
            $this->annotationReaderMock,
            $this->propertyAnonymizer
        );
    }

    /**
     * Tests if constructing a new Anonymizer instance sets the instance properties.
     *
     * @return void
     */
    public function testConstruct()
    {
        $this->assertAttributeSame($this->annotationReaderMock, 'annotationReader', $this->anonymizer);
        $this->assertAttributeSame($this->propertyAnonymizer, 'propertyAnonymizer', $this->anonymizer);
    }

    /**
     * Tests if Anonymizer::anonymize calls the annotation reader instance and property anonymizer instance.
     *
     * @return void
     */
    public function testAnonymizeObject()
    {
        $annotatedMock = new AnnotatedMock();
        $anonymize = new Anonymize();
        $anonymize->type = 'fixed';
        $anonymize->value = 'foo';

        $this->annotationReaderMock->expects($this->once())
            ->method('getPropertiesWithAnnotation')
            ->with(
                (new \ReflectionClass($annotatedMock)),
                Anonymize::class
            )
            ->willReturn([
                'foo' => $anonymize,
            ]);

        $this->propertyAnonymizer->expects($this->any())
            ->method('anonymizeField')
            ->with(
                $annotatedMock,
                'foo',
                $anonymize
            );

        $this->anonymizer->anonymize($annotatedMock);
    }

    /**
     * Test if Anonymizer can only handle objects as argument.
     *
     * @return void
     */
    public function testAnonymizerShouldReceiveExceptionWhenArgumentIsNoObject()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument given "string" should be of type object.');

        $this->anonymizer->anonymize('test');
    }
}
