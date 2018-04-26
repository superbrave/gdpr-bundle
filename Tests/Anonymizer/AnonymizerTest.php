<?php

namespace SuperBrave\GdprBundle\Tests\Anonymizer;

use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use SuperBrave\GdprBundle\Annotation\AnnotationReader;
use SuperBrave\GdprBundle\Anonymize\Anonymizer;
use SuperBrave\GdprBundle\Anonymize\PropertyAnonymizer;

class AnonymizerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Anonymizer
     */
    private $anonymizer;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $annotationReaderMock;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $propertyAnonymizer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->annotationReaderMock = $this->getMockBuilder(AnnotationReader::class)
            ->getMock();

        $this->propertyAnonymizer =  $this->getMockBuilder(PropertyAnonymizer::class)
            ->getMock();

        $this->anonymizer = new Anonymizer(
            $this->annotationReaderMock,
            $this->propertyAnonymizer
        );
    }

    /**
     * Tests if constructing a new Anonymizer instance sets the instance properties.
     */
    public function testConstruct()
    {
        $this->assertAttributeSame($this->annotationReaderMock, 'annotationReader', $this->anonymizer);
        $this->assertAttributeSame($this->propertyAnonymizer, 'propertyAnonymizer', $this->anonymizer);
    }
}