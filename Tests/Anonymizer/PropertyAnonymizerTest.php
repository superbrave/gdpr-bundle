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

namespace SuperBrave\GdprBundle\Tests\Anonymizer;

use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Reflectionclass;
use SuperBrave\GdprBundle\Annotation\Anonymize;
use SuperBrave\GdprBundle\Anonymizer\AnonymizerCollection;
use SuperBrave\GdprBundle\Anonymizer\AnonymizerInterface;
use SuperBrave\GdprBundle\Anonymizer\PropertyAnonymizer;
use SuperBrave\GdprBundle\Tests\AnnotatedMock;

/**
 * Test the behaviour of the PropertyAnonymizer
 */
class PropertyAnonymizerTest extends TestCase
{
    /**
     * Mock to test Annotations
     *
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

    /**
     * Test that the property anonymizer correctly utilizes the anonymizercollection to anonymize a property
     */
    public function testPropertyAnonymizerUsesAnonymizerCollection()
    {
        $reflectionProperty = $this->reflectionClass->getProperty('foo');
        $annotation = new Anonymize();
        $annotation->type = 'testtype';
        $annotation->value = 'testvalue';

        $anonymizerMock = $this->getMockBuilder(AnonymizerInterface::class)->getMock();

        $this->anonymizerCollection
            ->expects($this->once())
            ->method('getAnonymizer')
            ->with('testtype')
            ->willReturn($anonymizerMock);

        $anonymizerMock
            ->expects($this->once())
            ->method('anonymize')
            ->with($this->annotatedMock->getFoo())
            ->willReturn('anonymizedValue');

        $this->propertyAnonymizer->anonymizeField(
            $this->annotatedMock,
            $reflectionProperty,
            $annotation
        );

        $this->assertEquals('anonymizedValue', $this->annotatedMock->getFoo());
    }
}
