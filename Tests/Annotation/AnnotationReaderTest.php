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
    public function setUp()
    {
        $this->annotationReader = new AnnotationReader();

        // Ensure the Export annotation class is autoloaded, because the \Doctrine\Common\Annotations\DocParser
        // used in the AnnotationReader does not use the existing classloaders.
        // Only the AnnotationRegistry classloader.
        class_exists(Export::class);
    }

    /**
     * Tests if AnnotationReader::getPropertiesWithAnnotation returns a keyed array with the annotation instances.
     *
     * @return void
     */
    public function testGetPropertiesWithAnnotation()
    {
        $result = $this->annotationReader->getPropertiesWithAnnotation(
            new ReflectionClass(AnnotatedMock::class),
            Export::class
        );

        $this->assertInternalType('array', $result);
        $this->assertCount(5, $result);
        $this->assertSame(
            array('foo', 'baz', 'qux', 'quux', 'annotatedPropertyWithoutMethod'),
            array_keys($result)
        );
        $this->assertInstanceOf(Export::class, current($result));
    }
}
