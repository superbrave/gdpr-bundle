<?php
/**
 * This file is part of the GDPR bundle.
 *
 * @category  Bundle
 *
 * @author    SuperBrave <info@superbrave.nl>
 * @copyright 2018 SuperBrave <info@superbrave.nl>
 * @license   https://github.com/superbrave/gdpr-bundle/blob/master/LICENSE MIT
 *
 * @see       https://www.superbrave.nl/
 */

namespace Superbrave\GdprBundle\Tests\Anonymizer;

use PHPUnit\Framework\TestCase;
use Superbrave\GdprBundle\Anonymize\Type\IpAnonymizer;

/**
 * Class IpAnonymizerTest.
 */
class IpAnonymizerTest extends TestCase
{
    /**
     * Tests an IPv4 address with a default mask.
     *
     * @return void
     */
    public function testIpv4WithDefaultMask()
    {
        $anonymizer = new IpAnonymizer();

        $this->assertEquals('10.20.30.0', $anonymizer->anonymize('10.20.30.40', []));
    }

    /**
     * Tests an IPv6 address with a default mask.
     *
     * @return void
     */
    public function testIpv6WithDefaultMask()
    {
        $anonymizer = new IpAnonymizer();

        $this->assertEquals(
            '1234:5678:90ab::',
            $anonymizer->anonymize('1234:5678:90ab:cdef:1234:5678:90ab:cdef', [])
        );
    }

    /**
     * Tests an IPv4 address with a custom mask.
     *
     * @return void
     */
    public function testIpv4WithCustomMask()
    {
        $anonymizer = new IpAnonymizer('255.192.0.0');

        $this->assertEquals('10.128.0.0', $anonymizer->anonymize('10.140.30.40', []));
    }

    /**
     * Tests an IPv6 address with a custpm mask.
     *
     * @return void
     */
    public function testIpv6WithCustomMask()
    {
        $anonymizer = new IpAnonymizer('255.255.255.0', 'ffff:ffff:ffff:7730::');

        $this->assertEquals(
            '1234:5678:90ab:4520::',
            $anonymizer->anonymize('1234:5678:90ab:cdef:1234:5678:90ab:cdef', [])
        );
    }

    /**
     * Test an IPv4 as a long int using INET_ATON.
     *
     * @return void
     */
    public function testIpv4AsLong()
    {
        $anonymizer = new IpAnonymizer('255.192.0.0');

        $this->assertEquals(176160768, $anonymizer->anonymize(176954920, []));
    }
}
