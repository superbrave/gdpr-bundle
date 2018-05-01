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

namespace SuperBrave\GdprBundle\Anonymize\Type;

/**
 * DATE_RFC7231 is not natively supported in PHP 5.6
 *
 * @deprecated since PHP 7.0.19
 */
defined('DATE_RFC7231') or define('DATE_RFC7231', "D, d M Y H:i:s \G\M\T");

/**
 * DateTime anonymizer class
 *
 * @package SuperBrave\GdprBundle\Anonymize\Type
 */
class DateTimeAnonymizer implements AnonymizerInterface
{
    /**
     * Array with supported string formats
     *
     * Currently supported string formats are the ATOM, W3C, RSS, COOKIE, RFC822, RFC850, RFC1036, RFC1123, RFC2822,
     * RFC3339, RFC7231 and ISO8601 formats.
     *
     * Constants DATE_ATOM and DATE_W3C are the same as DATE_RFC3339;
     * constants DATE_RFC1036, DATE_RFC1123, DATE_RFC2822 and DATE_RSS are the same as DATE_RFC822;
     * constant DATE_COOKIE is the same as DATE_RFC850
     * so they're not added in this array
     *
     * @see http://php.net/manual/en/class.datetime.php#datetime.constants.types
     * @var array
     */
    private $stringFormats = [
        // PHP predefined standards
        DATE_ISO8601   => '/^[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}[+-]{1}[0-9]{4}$/',
        DATE_RFC822    => '/^[a-z]{3}, [0-9]{2} [a-z]{3} [0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2} [+-]{1}[0-9]{4}$/i',
        DATE_RFC850    => '/^[a-z]{4,}, [0-9]{2}-[a-z]{3}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2} [a-z]{1,}$/i',
        DATE_RFC7231   => '/^[a-z]{3}, [0-9]{2} [a-z]{3} [0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2} GMT$/i',
        DATE_RFC3339   => '/^[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}[+-]{1}[0-9]{2}:[0-9]{2}$/',
        DATE_RFC3339_EXTENDED =>
            '/^[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}\.[0-9]{3}[+-]{1}[0-9]{2}:[0-9]{2}$/',
        // Variants on ISO8601 which are used by different database storage designs
        'Y-m-d'        => '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/',
        'Y-m-d H:i:s'  => '/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/',
        'Y-m-d\TH:i:s' => '/^[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}$/',
    ];

    /**
     * Anonymizes a DateTime by setting month and day to 01 and hours, minutes and seconds to 00.
     *
     * The return value will be of the same type as $propertyValue.
     * Actual anonimizing will happen in {@see DateTimeAnonymizer::anonymizeByDateTime()}; string and int are converted.
     * Supported string formats are documented in {@see DateTimeAnonymizer::$stringFormats}
     *
     * @param \DateTime|int|string $propertyValue The value that has to be converted
     * @param array                $options       Options to help the anonymizer do its job
     *
     * @return \DateTime|int|string The anonymized result
     *
     * @throws \Exception When the $propertyValue is invalid, an exception will be thrown
     */
    public function anonymize($propertyValue, array $options = [])
    {
        // Regular DateTime object
        if ($propertyValue instanceof \DateTime) {
            return $this->anonymizeByDateTime($propertyValue);
        }

        // Unix timestamp
        if (is_numeric($propertyValue)) {
            $dateTime = new \DateTime(date('Y-m-d H:i:s', $propertyValue));
            return $this->anonymizeByDateTime($dateTime)->getTimestamp();
        }

        // String date
        if (is_string($propertyValue)) {
            $result = $this->anonymizeByString($propertyValue);
            if ($result !== false) {
                return $result;
            }
        }

        throw new \Exception("Invalid format of \$propertyValue for ".__CLASS__."::".__METHOD__);
    }

    /**
     * Anonymize a DateTime object by setting day and month to 1, hours, minutes and seconds to 0.
     *
     * @param \DateTime $dateTime Original DateTime object
     *
     * @return \DateTime
     */
    private function anonymizeByDateTime(\DateTime $dateTime)
    {
        // Clone the object, instead of modifying the $dateTime object.
        $result = clone $dateTime;
        $result->setDate($dateTime->format('Y'), 1, 1);
        $result->setTime(0, 0, 0);
        return $result;
    }

    /**
     * Anonymize a DateTime string by setting day and month to 1, hours, minutes and seconds to 0.
     *
     * Supported string formats are documented in {@see DateTimeAnonymizer::$stringFormats}
     *
     * @param string $dateTime The date/time as string
     *
     * @return string|boolean False on failure
     */
    private function anonymizeByString($dateTime)
    {
        foreach ($this->stringFormats as $dateFormat => $regexTest) {
            if (!preg_match($regexTest, $dateTime)) {
                continue;
            }
            $value = new \DateTime($dateTime);
            return $this->anonymizeByDateTime($value)->format($dateFormat);
        }

        // No regex matches the string; unknown datetime format
        return false;
    }
}
