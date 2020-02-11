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

namespace Superbrave\GdprBundle\Tests\Annotation;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Superbrave\GdprBundle\Annotation\AnnotationReader;
use Superbrave\GdprBundle\Annotation\Anonymize;
use Superbrave\GdprBundle\Annotation\Export;
use Superbrave\GdprBundle\Tests\AnnotatedMock;
use Superbrave\GdprBundle\Tests\ExtendedAnnotedMock;

/**
 * AnnotationReaderTest.
 *
 * @author Niels Nijens <nn@superbrave.nl>
 * @author Jelle van Oosterbosch <jvo@superbrave.nl>
 */
class AnnotationReaderTest extends TestCase
{
    /**
     * The AnnotationReader instance being tested.
     *
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * Creates a new AnnotationReader instance for testing.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->annotationReader = new AnnotationReader();

        // Ensure the Export annotation class is autoloaded, because the \Doctrine\Common\Annotations\DocParser
        // used in the AnnotationReader does not use the existing classloaders.
        // Only the AnnotationRegistry classloader.
        class_exists(Export::class);
        class_exists(Anonymize::class);
    }

    /**
     * Tests if AnnotationReader::getPropertiesWithAnnotation returns a keyed array with the annotation export
     * instances.
     *
     * @return void
     */
    public function testGetPropertiesWithAnnotationExport(): void
    {
        $result = $this->annotationReader->getPropertiesWithAnnotation(
            new ReflectionClass(AnnotatedMock::class),
            Export::class
        );

        $this->assertIsArray($result);
        $this->assertCount(6, $result);
        $this->assertSame(
            ['foo', 'baz', 'qux', 'quux', 'quuz', 'annotatedPropertyWithoutMethod'],
            array_keys($result)
        );
        $this->assertInstanceOf(Export::class, current($result));
    }

    /**
     * Tests if AnnotationReader::getPropertiesWithAnnotation returns a keyed array with the annotation anonymize
     * instances.
     *
     * @return void
     */
    public function testGetPropertiesWithAnnotationAnonymize(): void
    {
        $result = $this->annotationReader->getPropertiesWithAnnotation(
            new ReflectionClass(AnnotatedMock::class),
            Anonymize::class
        );

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertSame(
            ['foo'],
            array_keys($result)
        );
        $this->assertInstanceOf(Anonymize::class, current($result));
    }

    /**
     * Tests if AnnotationReader::getPropertiesWithAnnotation returns a keyed array with the annotation instances
     * of both the class and the parent class.
     *
     * @return void
     */
    public function testGetPropertiesWithAnnotationForExtendedClass(): void
    {
        $result = $this->annotationReader->getPropertiesWithAnnotation(
            new ReflectionClass(ExtendedAnnotedMock::class),
            Export::class
        );

        $this->assertIsArray($result);
        $this->assertCount(7, $result);
        $this->assertSame(
            ['extendedProperty', 'foo', 'baz', 'qux', 'quux', 'quuz', 'annotatedPropertyWithoutMethod'],
            array_keys($result)
        );

        $this->assertInstanceOf(Export::class, current($result));
    }
}
