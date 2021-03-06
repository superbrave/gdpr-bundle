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

namespace Superbrave\GdprBundle\Tests\Anonymizer;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Superbrave\GdprBundle\Annotation\Anonymize;
use Superbrave\GdprBundle\Anonymize\AnonymizerCollection;
use Superbrave\GdprBundle\Anonymize\PropertyAnonymizer;
use Superbrave\GdprBundle\Anonymize\Type\AnonymizerInterface;
use Superbrave\GdprBundle\Manipulator\PropertyManipulator;
use Superbrave\GdprBundle\Tests\AnnotatedMock;

/**
 * Test the behaviour of the PropertyAnonymizer.
 */
class PropertyAnonymizerTest extends TestCase
{
    /**
     * Mock for the PropertyManipulator.
     *
     * @var MockObject
     */
    private $propertyManipulatorMock;

    /**
     * Mock for the AnonymizerCollection.
     *
     * @var MockObject
     */
    private $anonymizerCollection;

    /**
     * The propertyAnonymizer to be tested.
     *
     * @var PropertyAnonymizer
     */
    private $propertyAnonymizer;

    /**
     * A ReflectionClass build from AnnotatedMock class.
     *
     * @var ReflectionClass
     */
    private $reflectionClass;

    /**
     * Sets up the properties to be used in further tests.
     *
     * @return void
     */
    protected function setUp(): void
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
     * Test that the property anonymizer correctly utilizes its dependencies to do its job.
     *
     * @return void
     */
    public function testPropertyAnonymizerUsesDependencies(): void
    {
        $annotation = new Anonymize();
        $annotation->type = 'testType';
        $annotation->value = 'testValue';

        $anonymizerMock = $this->getMockBuilder(AnonymizerInterface::class)->getMock();

        $theObject = new \stdClass();

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
