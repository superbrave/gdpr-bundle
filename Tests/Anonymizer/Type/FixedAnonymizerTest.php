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

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Superbrave\GdprBundle\Anonymize\Type\FixedAnonymizer;
use Superbrave\GdprBundle\Manipulator\PropertyManipulator;
use Superbrave\GdprBundle\Tests\AnnotatedMock;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Tests the behaviour of the EmailAnonymizer
 */
class FixedAnonymizerTest extends TestCase
{
    /**
     * The mock property accessor instance.
     *
     * @var MockObject
     *
     * @return void
     */
    private $propertyManipulator;

    /**
     * An instance of StringAnonymizer being tested.
     *
     * @var FixedAnonymizer
     *
     * @return void
     */
    private $anonymizer;

    /**
     * Creates a new AnnotationNormalizer instance for testing.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->propertyManipulator = new PropertyManipulator(
            PropertyAccess::createPropertyAccessor()
        );

        $this->anonymizer = new FixedAnonymizer($this->propertyManipulator);
    }

    /**
     * Tests if constructing a new StringAnonymizer sets the instance properties.
     *
     * @return void
     */
    public function testConstructHasCorrectProperty(): void
    {
        $this->assertAttributeSame($this->propertyManipulator, 'propertyManipulator', $this->anonymizer);
    }

    /**
     * An exception should be thrown when the annotationValue isn't provided.
     *
     * @return void
     */
    public function testAnonymizerShouldReceiveAnnotationValueOptionOrThrowException(): void
    {
        $this->expectException(MissingOptionsException::class);
        $this->expectExceptionMessage('The required option "annotationValue" is missing.');

        $this->anonymizer->anonymize('johndoe@appleseed.com', [
            'object' => new AnnotatedMock(),
        ]);
    }

    /**
     * An exception should be thrown when the object option isn't provided
     *
     * @return void
     */
    public function testAnonymizerShouldReceiveObjectOptionOrThrowException(): void
    {
        $this->expectException(MissingOptionsException::class);
        $this->expectExceptionMessage('The required option "object" is missing.');

        $this->anonymizer->anonymize('johndoe@appleseed.com', [
            'annotationValue' => 'foobar',
        ]);
    }

    /**
     * Test wether or not getting the wildcards properties will be replaced with their values
     *
     * @return void
     */
    public function testAnonymizeStringGivenAFormatWithMultipleProperties(): void
    {
        $this->assertEquals(
            'email-1-bar@localhost',
            $this->anonymizer->anonymize('johndoe@appleseed.com', [
                'annotationValue' => 'email-{baz}-{foo}@localhost',
                'object' => new AnnotatedMock(),
            ])
        );
    }

    /**
     * Test that an exception is thrown when the object does not have the specified property
     *
     * @return void
     */
    public function testAnonymizeStringGivenAFormatWithInvalidPropertiesThrowsAnException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'The property "$nonexistent" does not exist on class "Superbrave\GdprBundle\Tests\AnnotatedMock"'
        );

        $this->anonymizer->anonymize('johndoe@appleseed.com', [
            'annotationValue' => 'email-{nonexistent}',
            'object' => new AnnotatedMock(),
        ]);
    }

    /**
     * Test that the value is returned if there aren't any wildcards
     *
     * @return void
     */
    public function testAnonymizeStringGivenAFormatWithNoWildcardReturnsTheString(): void
    {
        $this->assertEquals('foobarbaz', $this->anonymizer->anonymize('johndoe@appleseed.com', [
            'annotationValue' => 'foobarbaz',
            'object' => new AnnotatedMock(),
        ]));
    }

    /**
     * Tests that an exception is thrown when the value provided in the annotation is an empty string
     * Type "fixed" is expected to receive a value
     *
     * @return void
     */
    public function testAnonymizeRequiresTheValueToBeANonEmptyStringOrThrowException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The option "annotationValue" cannot be empty');

        $this->anonymizer->anonymize('johndoe@appleseed.com', [
            'annotationValue' => null,
            'object' => new AnnotatedMock(),
        ]);
    }
}
