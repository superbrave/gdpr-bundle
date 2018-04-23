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

namespace SuperBrave\GdprBundle\Anonymizer;

use InvalidArgumentException;

class IpAnonymizer implements AnonymizerInterface
{
    /**
     * @var string
     */
    private $ipv4Mask;

    /**
     * @var string
     */
    private $ipv6Mask;

    /**
     * IpAnonymizer constructor.
     *
     * @param string $ipv4Mask
     * @param string $ipv6Mask
     */
    public function __construct($ipv4Mask = '255.255.255.0', $ipv6Mask = 'ffff:ffff:ffff::')
    {
        $this->ipv4Mask = $ipv4Mask;
        $this->ipv6Mask = $ipv6Mask;
    }

    /**
     * {@inheritdoc}
     */
    public function anonymize($propertyValue, array $options = [])
    {
        $ipv4 = filter_var($propertyValue, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        $ipv6 = filter_var($propertyValue, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);

        if (($ipv4 === false) && ($ipv6 === false)) {
            throw new InvalidArgumentException(sprintf('%s is not a valid ipv4 or ipv6 address', $propertyValue));
        }

        return $ipv4 ? $this->anonymizeIpAddress($propertyValue, $this->ipv4Mask) : $this->anonymizeIpAddress($propertyValue, $this->ipv6Mask);
    }

    /**
     * @param $address
     * @param $mask
     * @return string
     */
    private function anonymizeIpAddress($address, $mask)
    {
        $maskBytes = unpack('C*', inet_pton($mask));
        $addressBytes = unpack('C*', inet_pton($address));
        $anonymizedAddress = '';
        foreach ($addressBytes as $index => $byte) {
            $anonymizedAddress .= chr($byte & $maskBytes[$index]);
        }

        return inet_ntop($anonymizedAddress);
    }
}
