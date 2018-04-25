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
use SuperBrave\GdprBundle\Anonymize\Type\IpAnonymizer;

class IpAnonymizerTest extends TestCase
{
    public function testIpv4WithDefaultMask()
    {
        $anonymizer = new IpAnonymizer();

        $this->assertEquals('10.20.30.0', $anonymizer->anonymize('10.20.30.40', []));
    }

    public function testIpv6WithDefaultMask()
    {
        $anonymizer = new IpAnonymizer();

        $this->assertEquals('1234:5678:90ab::', $anonymizer->anonymize('1234:5678:90ab:cdef:1234:5678:90ab:cdef', []));
    }
    public function testIpv4WithCustomMask()
    {
        $anonymizer = new IpAnonymizer('255.192.0.0');

        $this->assertEquals('10.128.0.0', $anonymizer->anonymize('10.140.30.40', []));
    }

    public function testIpv6WithCustomMask()
    {
        $anonymizer = new IpAnonymizer('255.255.255.0', 'ffff:ffff:ffff:7730::');

        $this->assertEquals('1234:5678:90ab:4520::', $anonymizer->anonymize('1234:5678:90ab:cdef:1234:5678:90ab:cdef', []));
    }

    public function testIpv4AsLong()
    {
        $anonymizer = new IpAnonymizer('255.192.0.0');

        $this->assertEquals(176160768, $anonymizer->anonymize(176954920, []));
    }
}
