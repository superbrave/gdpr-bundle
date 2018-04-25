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
use SuperBrave\GdprBundle\Manipulator\PropertyManipulator;
use SuperBrave\GdprBundle\Tests\AnnotatedMock;

/**
 * Test the behaviour of the PropertyAnonymizer
 */
class PropertyAnonymizerTest extends TestCase
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $propertyManipulatorMock;

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
        $this->anonymizerCollection = $this->getMockBuilder(AnonymizerCollection::class)
            ->getMock();

        $this->propertyManipulatorMock = $this->getMockBuilder(PropertyManipulator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->propertyAnonymizer = new PropertyAnonymizer($this->propertyManipulatorMock, $this->anonymizerCollection);

        $this->reflectionClass = new ReflectionClass(AnnotatedMock::class);
    }

    /**
     * Test that the property anonymizer correctly utilizes its dependencies to do its job
     */
    public function testPropertyAnonymizerUsesDependencies()
    {
        $annotation = new Anonymize();
        $annotation->type = 'testType';
        $annotation->value = 'testValue';

        $anonymizerMock = $this->getMockBuilder(AnonymizerInterface::class)->getMock();

        $theObject = new \StdClass();

        //first it uses the collection to find an anonymizer
        $this->anonymizerCollection
            ->expects($this->once())
            ->method('getAnonymizer')
            ->with('testType')
            ->willReturn($anonymizerMock);

        //then it uses the manipulator to get the property's value
        $this->propertyManipulatorMock
            ->expects($this->once())
            ->method('getPropertyValue')
            ->with($theObject, 'testProperty')
            ->willReturn('testValue');

        //after that the anonymizer is used to anonymize the value
        $anonymizerMock
            ->expects($this->once())
            ->method('anonymize')
            ->with('testValue')
            ->willReturn('anonymizedValue');

        //and last the anonymized value should be handed back to the manipulator to update the object
        $this->propertyManipulatorMock
            ->expects($this->once())
            ->method('setPropertyValue')
            ->with($theObject, 'testProperty', 'anonymizedValue');

        $this->propertyAnonymizer->anonymizeField(
            $theObject,
            'testProperty',
            $annotation
        );
    }
}
