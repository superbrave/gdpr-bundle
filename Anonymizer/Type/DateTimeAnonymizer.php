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

namespace SuperBrave\GdprBundle\Anonymizer\Type;

use SuperBrave\GdprBundle\Anonymizer\AnonymizerInterface;

/**
 * DateTime anonymizer class
 *
 * @package SuperBrave\GdprBundle\Anonymizer
 */
class DateTimeAnonymizer implements AnonymizerInterface
{
    /**
     * Array with supported string formats
     *
     * Constants DATE_ATOM, DATE_RFC3339 and DATE_W3C are the same, so they're not added in this array
     *
     * @var array
     */
    private $stringFormats = [
        // PHP predefined standards
        DATE_RFC3339   => '/^[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}[+-]{1}[0-9]{2}:[0-9]{2}$/',
        DATE_ISO8601   => '/^[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}[+-]{1}[0-9]{4}$/',

        // Variants on ISO8601 which are used by different database storage designs
        'Y-m-d'        => '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/',
        'Y-m-d H:i:s'  => '/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/',
        'Y-m-d\TH:i:s' => '/^[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}$/',
    ];

    /**
     * Anonymizes a DateTime by setting month and day to 01 and hours, minutes and seconds to 00.
     *
     * The return value will be of the same type as $propertyValue.
     * All anonimizing will happen in {@see DateTimeAnonymizer::anonymizeByDateTime()}
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
            $dateTime = new \DateTime();
            $dateTime->setTimestamp($propertyValue);
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
     * Currently supported string formats are the ATOM, W3C, RFC3339 and ISO8601 formats.
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
