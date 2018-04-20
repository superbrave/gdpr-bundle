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

/**
 * tests the behaviour of the EmailAnonymizer
 */
class EmailAnonymizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * There should be an error when there is no id specified.
     * An id is needed to anonymize an email field
     */
    public function test_I_expect_to_receive_an_error_if_there_is_no_id_specified()
    {
        #TODO Fill this before making the anonymizer itself
    }
}
