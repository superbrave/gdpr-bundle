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

use PHPUnit_Framework_MockObject_MockObject;
use SuperBrave\GdprBundle\Anonymizer\Type\FixedValueAnonymizer;
use SuperBrave\GdprBundle\Manipulator\PropertyManipulator;
use SuperBrave\GdprBundle\Tests\AnnotatedMock;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * tests the behaviour of the EmailAnonymizer
 */
class FixedValueAnonymizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The mock property accessor instance.
     *
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $propertyManipulator;

    /**
     * An instance of StringAnonymizer being tested
     *
     * @var FixedValueAnonymizer
     */
    private $anonymizer;

    /**
     * Creates a new AnnotationNormalizer instance for testing.
     *
     * @return void
     */
    public function setUp()
    {
        $this->propertyManipulator = new PropertyManipulator(
            PropertyAccess::createPropertyAccessor()
        );

        $this->anonymizer = new FixedValueAnonymizer($this->propertyManipulator);
    }

    /**
     * Tests if constructing a new StringAnonymizer sets the instance properties.
     *
     * @return void
     */
    public function testConstructHasCorrectProperty()
    {
        $this->assertAttributeSame($this->propertyManipulator, 'propertyManipulator', $this->anonymizer);
    }

    public function testAnonymizerShouldReceiveAnnotationValueOptionOrThrowException()
    {
        $this->expectException(MissingOptionsException::class);
        $this->expectExceptionMessage('The required option "annotationValue" is missing.');

        $mock = new AnnotatedMock();

        $this->anonymizer->anonymize('johndoe@appleseed.com', [
            'object' => $mock,
        ]);
    }

    public function testAnonymizerShouldReceiveObjectOptionOrThrowException()
    {
        $this->expectException(MissingOptionsException::class);
        $this->expectExceptionMessage('The required option "object" is missing.');

        $this->anonymizer->anonymize('johndoe@appleseed.com', [
            'annotationValue' => 'foobar',
        ]);
    }

    /**
     * Test wether or not getting the wildcards properties will be replaced with their values
     */
    public function testAnonymizeStringGivenAFormatWithMultipleProperties()
    {
        $mock = new AnnotatedMock();

        $this->assertEquals('email-1-bar@localhost', $this->anonymizer->anonymize('johndoe@appleseed.com', [
            'annotationValue' => 'email-{baz}-{foo}@localhost',
            'object' => $mock,
        ]));
    }

    /**
     * Test that an exception is thrown when the object does not have the specified property
     */
    public function testAnonymizeStringGivenAFormatWithInvalidPropertiesThrowsAnException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The property "$nonexistent" does not exist on class "SuperBrave\GdprBundle\Tests\AnnotatedMock"');

        $mock = new AnnotatedMock();

        $this->anonymizer->anonymize('johndoe@appleseed.com', [
            'annotationValue' => 'email-{nonexistent}',
            'object' => $mock,
        ]);
    }

    /**
     * Test that the value is returned if there aren't any wildcards
     */
    public function testAnonymizeStringGivenAFormatWithNoWildcardReturnsTheString()
    {
        $mock = new AnnotatedMock();

        $this->assertEquals('foobarbaz', $this->anonymizer->anonymize('johndoe@appleseed.com', [
            'annotationValue' => 'foobarbaz',
            'object' => $mock,
        ]));
    }
}