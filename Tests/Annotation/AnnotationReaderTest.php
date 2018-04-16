<?php

namespace SuperBrave\GdprBundle\Tests\Annotation;

use PHPUnit_Framework_TestCase;
use ReflectionClass;
use SuperBrave\GdprBundle\Annotation\AnnotationReader;
use SuperBrave\GdprBundle\Annotation\Export;
use SuperBrave\GdprBundle\Tests\AnnotatedMock;

/**
 * AnnotationReaderTest.
 *
 * @author Niels Nijens <nn@superbrave.nl>
 */
class AnnotationReaderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * Creates a new AnnotationReader instance for testing.
     */
    public function setUp()
    {
        $this->annotationReader = new AnnotationReader();

        // Ensure the Export annotation class is autoloaded.
        class_exists(Export::class);
    }

    /**
     * Tests if AnnotationReader::getPropertiesWithAnnotation returns a keyed array with the annotation instances.
     */
    public function testGetPropertiesWithAnnotation()
    {
        $result = $this->annotationReader->getPropertiesWithAnnotation(
            new ReflectionClass(AnnotatedMock::class),
            Export::class
        );

        $this->assertInternalType('array', $result);
        $this->assertCount(4, $result);
        $this->assertSame(
            array('foo', 'baz', 'qux', 'quux'),
            array_keys($result)
        );
        $this->assertInstanceOf(Export::class, current($result));
    }
}
