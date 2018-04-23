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

use SuperBrave\GdprBundle\Anonymizer\EmailAnonymizer;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

/**
 * tests the behaviour of the EmailAnonymizer
 */
class EmailAnonymizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The ID is a required option
     * Without them the anonymizer cannot do its job
     *
     * We test if we get an exception when the ID option is not specified.
     */
    public function testIdIsARequiredOption()
    {
        $this->expectException(MissingOptionsException::class);
        $this->expectExceptionMessage('The required option "id" is missing.');

        $anonymizer = new EmailAnonymizer();
        $anonymizer->anonymize('johndoe@appleseed.com', [
            'fieldName' => 'email',
        ]);
    }

    /**
     * The fieldName is a required option
     * Without them the anonymizer cannot do its job
     *
     * We test if we get an exception when the fieldName option is not specified.
     */
    public function testFieldNameIsARequiredOption()
    {
        $this->expectException(MissingOptionsException::class);
        $this->expectExceptionMessage('The required option "fieldName" is missing.');

        $anonymizer = new EmailAnonymizer();
        $anonymizer->anonymize('johndoe@appleseed.com', [
            'id' => 10,
        ]);
    }

    public function testIdOptionshasToBeAnInteger()
    {
        $this->expectException(InvalidOptionsException::class);
        $this->expectExceptionMessage('The option "id" with value "invalid" is expected to be of type "int"');

        $anonymizer = new EmailAnonymizer();
        $anonymizer->anonymize('johndoe@appleseed.com', [
            'id' => 'invalid',
            'fieldName' => 'email',
        ]);
    }

    public function testFieldOptionshasToBeAnString()
    {
        $this->expectException(InvalidOptionsException::class);
        $this->expectExceptionMessage('The option "fieldName" with value 1337 is expected to be of type "string"');

        $anonymizer = new EmailAnonymizer();
        $anonymizer->anonymize('johndoe@appleseed.com', [
            'id' => 10,
            'fieldName' => 1337,
        ]);
    }

    /**
     * There should be an error when there is no id specified.
     * An id is needed to anonymize an email field
     */
    public function testAnonymizeEmailGivenTheFieldNameAndIdIsSuccessful()
    {
        $anonymizer = new EmailAnonymizer();

        $this->assertEquals('email-10@localhost', $anonymizer->anonymize('johndoe@appleseed.com', [
            'id' => 10,
            'fieldName' => 'email',
        ]));
    }
}
