<?php

namespace SuperBrave\GdprBundle\Tests\Anonymizer;

use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use SuperBrave\GdprBundle\Annotation\AnnotationReader;
use SuperBrave\GdprBundle\Annotation\Anonymize;
use SuperBrave\GdprBundle\Anonymize\Anonymizer;
use SuperBrave\GdprBundle\Anonymize\PropertyAnonymizer;
use SuperBrave\GdprBundle\Tests\AnnotatedMock;

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

        $this->propertyAnonymizer =  $this->getMockBuilder(PropertyAnonymizer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->anonymizer = new Anonymizer(
            $this->annotationReaderMock,
            $this->propertyAnonymizer
        );
    }

    /**
     * Tests if constructing a new Anonymizer instance sets the instance properties.
     */
    public function testConstruct()
    {
        $this->assertAttributeSame($this->annotationReaderMock, 'annotationReader', $this->anonymizer);
        $this->assertAttributeSame($this->propertyAnonymizer, 'propertyAnonymizer', $this->anonymizer);
    }

    /**
     * Tests if Anonymizer::anonymize calls the annotation reader instance and property anonymizer instance.
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
}