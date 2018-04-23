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
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

/**
 * Tests the behaviour of the FixedValueAnonynizer
 */
class FixedValueAnonymizerTest extends PHPUnit_Framework_TestCase
{
    /**
     * The "annotationValue" option is required for this anonymier to work
     *
     * @return void
     */
    public function testAnnotationValueIsARequiredOption()
    {
        $this->expectException(MissingOptionsException::class);
        $this->expectExceptionMessage('The required option "annotationValue" is missing');

        $anonymizer = new FixedValueAnonymizer();
        $anonymizer->anonymize('johndoe');
    }

    /**
     * Tests the anonymization with a fixed value
     *
     * The returned value hould be the same as the provided one
     *
     * @return void
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
