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

use PHPUnit_Framework_TestCase;
use SuperBrave\GdprBundle\Anonymizer\FixedValueAnonymizer;

/**
 * tests the behaviour of the FixedValueAnonynizer
 */
class FixedValueAnonymizerTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tests the anonymization with a fixed value
     *
     * The returned value hould be the same as the provided one
     */
    public function testAnonymize()
    {
        $anonymizer = new FixedValueAnonymizer();

        $propertyValue = 'foobar';
        $annotationValue = 'testvalue';

        $this->assertEquals($annotationValue, $anonymizer->anonymize($propertyValue, [
            'annotationValue' => $annotationValue
        ]));
    }
}
