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

use SuperBrave\GdprBundle\Annotation\Anonymize;

/**
 * DateTime anonymizer class
 *
 * @package SuperBrave\GdprBundle\Anonymizer
 */
class DateTimeAnonymizer implements AnonymizerInterface
{
    /**
     * Anonymizes a DateTime by setting month and day to 01 and hours, minutes and seconds to 00.
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
        if (is_string($propertyValue) && $result = $this->anonymizeByString($propertyValue)) {
            return $result;
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
     * @param string $dateTime The date/time as string in format "yyyy-mm-dd hh:ii:ss" or "yyyy-mm-dd"
     *
     * @return string|boolean False on failure
     */
    private function anonymizeByString($dateTime)
    {
        $matches = array();

        // Splits up the string in year, month, day, hours, minutes and seconds
        preg_match(
            '/([0-9]{2,4}-)([0-9]{2})(-)([0-9]{2})([\s|T]{1})([0-9]{2})(:)([0-9]{2})(:)([0-9]{2})/',
            $dateTime,
            $matches
        );
        if ($matches) {
            // Resets specific keys
            $matches[0] = '';                                // Remove original string
            $matches[2] = $matches[4] = '01';                // month, day
            $matches[6] = $matches[8] = $matches[10] = '00'; // hours, minutes, seconds

            // Glues the parts together again; separators will be unmodified
            return implode('', $matches);
        }

        // Splits up the string in year, month and day
        preg_match('/([0-9]{2,4}-)([0-9]{2})(-)([0-9]{2})/', $dateTime, $matches);
        if ($matches) {
            // Resets specific keys
            $matches[0] = '';                 // Remove original string
            $matches[2] = $matches[4] = '01'; // month, day

            // Glues the parts together again; separators will be unmodified
            return implode('', $matches);
        }

        // No regex matches the string; unknown datetime format
        return false;
    }
}
