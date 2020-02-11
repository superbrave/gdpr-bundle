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

namespace Superbrave\GdprBundle\Tests\Serializer\Normalizer;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Superbrave\GdprBundle\Annotation\AnnotationReader;
use Superbrave\GdprBundle\Annotation\Export;
use Superbrave\GdprBundle\Manipulator\PropertyManipulator;
use Superbrave\GdprBundle\Serializer\Normalizer\AnnotationNormalizer;
use Superbrave\GdprBundle\Serializer\Normalizer\IterableNormalizer;
use Superbrave\GdprBundle\Tests\AnnotatedMock;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * AnnotationNormalizerTest.
 *
 * @author Niels Nijens <nn@superbrave.nl>
 * @author Jelle van Oosterbosch <jvo@superbrave.nl>
 */
class AnnotationNormalizerTest extends TestCase
{
    /**
     * The AnnotationNormalizer instance being tested.
     *
     * @var AnnotationNormalizer
     */
    private $normalizer;

    /**
     * The mock AnnotationReader instance.
     *
     * @var MockObject
     */
    private $annotationReaderMock;

    /**
     * The mock property accessor instance.
     *
     * @var MockObject
     */
    private $propertyManipulatorMock;

    /**
     * Creates a new AnnotationNormalizer instance for testing.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->annotationReaderMock = $this->getMockBuilder(AnnotationReader::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->propertyManipulatorMock = $this->getMockBuilder(PropertyManipulator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->normalizer = new AnnotationNormalizer(
            $this->annotationReaderMock,
            Export::class,
            $this->propertyManipulatorMock
        );
    }

    /**
     * Tests if constructing a new AnnotationNormalizer sets the instance properties.
     *
     * @return void
     */
    public function testConstruct(): void
    {
        $this->assertAttributeSame($this->annotationReaderMock, 'annotationReader', $this->normalizer);
        $this->assertAttributeSame(Export::class, 'annotationName', $this->normalizer);
        $this->assertAttributeSame($this->propertyManipulatorMock, 'propertyManipulator', $this->normalizer);
    }

    /**
     * Tests if AnnotationNormalizer::supportsNormalization returns false
     * when the data is not an object.
     *
     * @return void
     */
    public function testSupportsNormalizationReturnsFalseWhenDataIsNotAnObject(): void
    {
        $this->annotationReaderMock->expects($this->never())
            ->method('getPropertiesWithAnnotation');

        $this->assertFalse($this->normalizer->supportsNormalization('no object'));
    }

    /**
     * Tests if AnnotationNormalizer::supportsNormalization returns false
     * when the AnnotationReader does not return annotation instances.
     *
     * @return void
     */
    public function testSupportsNormalizationReturnsFalseWhenAnnotationReaderDoesNotReturnAnnotations(): void
    {
        $this->annotationReaderMock->expects($this->once())
            ->method('getPropertiesWithAnnotation')
            ->with(
                $this->isInstanceOf(ReflectionClass::class),
                Export::class
            )
            ->willReturn([]);

        $this->assertFalse($this->normalizer->supportsNormalization(new AnnotatedMock()));
    }

    /**
     * Tests if AnnotationNormalizer::supportsNormalization returns true
     * when the AnnotationReader returns annotation instances.
     *
     * @return void
     */
    public function testSupportsNormalizationReturnsTrueWhenAnnotationReaderReturnsAnnotations(): void
    {
        $this->annotationReaderMock->expects($this->once())
            ->method('getPropertiesWithAnnotation')
            ->with(
                $this->isInstanceOf(ReflectionClass::class),
                Export::class
            )
            ->willReturn([
                'foo' => new Export(),
            ]);

        $this->assertTrue($this->normalizer->supportsNormalization(new AnnotatedMock()));
    }

    /**
     * Tests if AnnotationNormalizer::normalize returns the expected array of a AnnotatedMock instance.
     *
     * @return void
     */
    public function testNormalize(): void
    {
        $annotationReader = new AnnotationReader();
        $propertyManipulator = new PropertyManipulator(
            PropertyAccess::createPropertyAccessor()
        );

        $normalizer = new AnnotationNormalizer($annotationReader, Export::class, $propertyManipulator);
        $serializer = new Serializer([
            new DateTimeNormalizer(),
            new IterableNormalizer(),
            $normalizer,
        ]);
        $normalizer->setNormalizer($serializer);

        $annotatedMock = new AnnotatedMock();

        $this->assertEquals(
            [
                'foo' => 'bar',
                'baz' => 1,
                'qux' => [],
                'quuxs' => [],
                'quuz' => '2016-01-01T00:00:00+00:00',
                'annotatedPropertyWithoutMethod' => 'Yes',
            ],
            $normalizer->normalize($annotatedMock)
        );
    }

    /**
     * Tests if @see AnnotationNormalizer::normalize returns the expected xml normalized data
     * for serialization through the Serializer.
     *
     * @return void
     */
    public function testNormalizeThroughXmlSerializer(): void
    {
        $annotationReader = new AnnotationReader();
        $propertyManipulator = new PropertyManipulator(
            PropertyAccess::createPropertyAccessor()
        );

        $normalizers = [
            new DateTimeNormalizer(),
            new IterableNormalizer(),
            new AnnotationNormalizer($annotationReader, Export::class, $propertyManipulator),
        ];
        $encoders = [new XmlEncoder('mock')];

        $serializer = new Serializer(
            $normalizers,
            $encoders
        );

        $data = new AnnotatedMock(new AnnotatedMock());

        $this->assertStringEqualsFile(
            __DIR__ . '/../../Resources/xml/annotation_normalizer_result.xml',
            $serializer->serialize($data, 'xml')
        );
    }

    /**
     * Test if @see AnnotationNormalizer::normalize returns the expected json normalized data
     * for serialization through the Serializer.
     *
     * @return void
     */
    public function testNormalizeThroughJsonSerializer(): void
    {
        $annotationReader = new AnnotationReader();
        $propertyManipulator = new PropertyManipulator(
            PropertyAccess::createPropertyAccessor()
        );

        $normalizers = [
            new DateTimeNormalizer(),
            new IterableNormalizer(),
            new AnnotationNormalizer($annotationReader, Export::class, $propertyManipulator),
        ];
        $encoders = [new JsonEncoder()];

        $serializer = new Serializer(
            $normalizers,
            $encoders
        );

        $data = new AnnotatedMock(new AnnotatedMock());

        $this->assertStringEqualsFile(
            __DIR__ . '/../../Resources/json/annotation_normalize_result.json',
            $serializer->serialize($data, 'json')
        );
    }
}
