<?php

namespace SuperBrave\GdprBundle\Tests\Anonymizer;

use PHPUnit_Framework_TestCase;
use SuperBrave\GdprBundle\Anonymizer\FixedValueAnonymizer;

class FixedValueAnonymizerTest extends PHPUnit_Framework_TestCase
{
    public function testAnonymize()
    {
        $anonymizer = new FixedValueAnonymizer();

        $propertyValue = '';
        $annotationValue = 'test';

        $this->assertEquals($annotationValue, $anonymizer->anonymize($propertyValue, $annotationValue));
    }
}
