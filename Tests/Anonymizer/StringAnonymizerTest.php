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

use SuperBrave\GdprBundle\Anonymizer\StringAnonymizer;
use SuperBrave\GdprBundle\Tests\AnnotatedMock;

/**
 * tests the behaviour of the EmailAnonymizer
 */
class StringAnonymizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * There should be an error when there is no id specified.
     * An id is needed to anonymize an email field
     */
    public function testAnonymizeStringGivenAEmailFormat()
    {
        $anonymizer = new StringAnonymizer();
        $mock = new AnnotatedMock();

        $this->assertEquals('email-1@localhost', $anonymizer->anonymize('johndoe@appleseed.com', [
            'annotationValue' => 'email-{baz}',
            'object' => $mock,
        ]));
    }
}
