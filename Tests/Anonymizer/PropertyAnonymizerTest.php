<?php

namespace SuperBrave\GdprBundle\Tests\Anonymizer;

use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Reflectionclass;
use SuperBrave\GdprBundle\Annotation\Anonymize;
use SuperBrave\GdprBundle\Anonymizer\AnonymizerCollection;
use SuperBrave\GdprBundle\Anonymizer\AnonymizerInterface;
use SuperBrave\GdprBundle\Anonymizer\PropertyAnonymizer;
use SuperBrave\GdprBundle\Tests\AnnotatedMock;

class PropertyAnonymizerTest extends TestCase
{
    /**
     * @var AnnotatedMock
     */
    private $annotatedMock;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $anonymizerCollection;

    /**
     * @var PropertyAnonymizer
     */
    private $propertyAnonymizer;

    /**
     * @var ReflectionClass
     */
    private $reflectionClass;

    public function setUp()
    {
        $this->annotatedMock = new AnnotatedMock();

        $this->anonymizerCollection = $this->getMockBuilder(AnonymizerCollection::class)
            ->getMock();

        $this->propertyAnonymizer = new PropertyAnonymizer($this->anonymizerCollection);

        $this->reflectionClass = new ReflectionClass(AnnotatedMock::class);
    }

    public function testSomething()
    {
        $reflectionProperty = $this->reflectionClass->getProperty('foo');
        $annotation = new Anonymize(['type' => 'testtype', 'value' => 'testvalue']);

        $anonymizerMock = $this->getMockBuilder(AnonymizerInterface::class)->getMock();

        $this->anonymizerCollection
            ->expects($this->once())
            ->method('getAnonymizer')
            ->with('testtype')
            ->willReturn($anonymizerMock);

        $anonymizerMock
            ->expects($this->once())
            ->method('anonymize')
            ->with($this->annotatedMock->getFoo(), 'testvalue')
            ->willReturn('anonymizedValue');

        $this->propertyAnonymizer->anonymizeField(
            $this->annotatedMock,
            $reflectionProperty,
            $annotation
        );

        $this->assertEquals('anonymizedValue', $this->annotatedMock->getFoo());
    }
}
