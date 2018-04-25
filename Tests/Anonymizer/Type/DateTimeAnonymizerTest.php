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

use PHPUnit\Framework\TestCase;
use SuperBrave\GdprBundle\Anonymizer\Type\DateTimeAnonymizer;

/**
 * Class DateTimeAnonymizerTest
 *
 * @package Gdpr
 */
class DateTimeAnonymizerTest extends TestCase
{
    /**
     * Tests the DateTime anonymizer
     *
     * @param \DateTime|int|string $testDate       The original date/time set
     * @param \DateTime|int|string $expectedResult The expected result after anonymizing the data
     *
     * @return void
     *
     * @dataProvider dateTimeDataProvider
     */
    public function testDateTime($testDate, $expectedResult)
    {
        $anonymizer = new DateTimeAnonymizer();
        $this->assertEquals($anonymizer->anonymize($testDate), $expectedResult);
    }

    /**
     * Returns a list of test cases for the testDateTime test
     *
     * @return array
     */
    public function dateTimeDataProvider()
    {
        return array(
            // DateTime objects
            'datetime1' => [new \DateTime('1983-08-11'), new \DateTime('1983-01-01')],
            'datetime2' => [new \DateTime('2016-04-27'), new \DateTime('2016-01-01')],
            'datetime3' => [new \DateTime('2018-05-25 13:37:00'), new \DateTime('2018-01-01 00:00:00')],

            // Timestamps
            'timestamp1' => [strtotime("1983-08-11"), strtotime("1983-01-01")],
            'timestamp2' => [strtotime("2016-04-27"), strtotime("2016-01-01")],
            'timestamp3' => [strtotime("2018-05-25 13:37:00"), strtotime("2018-01-01 00:00:00")],
            'timestamp4' => [strtotime("1945-05-05"), strtotime("1945-01-01")],

            // Several ISO8601 string formats
            'stringdate1' => ["1983-08-11", "1983-01-01"],
            'stringdate2' => ["2016-04-27", "2016-01-01"],
            'stringdate3' => ["2018-05-25 13:37:00", "2018-01-01 00:00:00"],
            'stringdate4' => ["2018-05-25T13:37:00", "2018-01-01T00:00:00"],
            'stringdate5' => ["2018-05-25T13:37:00+0200", "2018-01-01T00:00:00+0200"],
            // RFC3339 string format
            'stringdate6' => ["2018-05-25T13:37:00+02:00", "2018-01-01T00:00:00+02:00"],
        );
    }
}
