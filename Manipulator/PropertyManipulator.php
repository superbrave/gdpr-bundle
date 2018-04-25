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

namespace SuperBrave\GdprBundle\Manipulator;

use ReflectionProperty;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Class PropertyManipulator
 *
 * @package SuperBrave\GdprBundle\Manipulator
 */
class PropertyManipulator
{
    private $propertyAccessor;

    /**
     * Constructs a new PropertyManipulator instance.
     *
     * @param PropertyAccessorInterface $propertyAccessor The property accessor instance
     */
    public function __construct(PropertyAccessorInterface $propertyAccessor)
    {
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * Returns the value of specified property
     *
     * @param object $object       The object containing the property
     * @param string $propertyName The property name where the value is taken from
     *
     * @return mixed
     */
    public function getPropertyValue($object, $propertyName)
    {
        try {
            $propertyData = $this->propertyAccessor->getValue($object, $propertyName);
        } catch (NoSuchPropertyException $exception) {
            try {
                $reflectionProperty = new ReflectionProperty($object, $propertyName);
                $reflectionProperty->setAccessible(true);

                $propertyData = $reflectionProperty->getValue($object);
            } catch (\Exception $e) {
                throw new \InvalidArgumentException(
                    sprintf('The property "$%s" does not exist on class "%s"', $propertyName, get_class($object))
                );
            }
        }

        return $propertyData;
    }

    /**
     * Sets a new value on the specified property
     *
     * @param object $object       The object containing the property
     * @param string $propertyName The property name where the value is written to
     * @param mixed  $value        The new value for the property
     *
     * @return void
     */
    public function setPropertyValue($object, $propertyName, $value)
    {
        try {
            $this->propertyAccessor->setValue($object, $propertyName, $value);
        } catch (NoSuchPropertyException $exception) {
            try {
                $reflectionProperty = new ReflectionProperty($object, $propertyName);
                $reflectionProperty->setAccessible(true);

                $reflectionProperty->setValue($object, $value);
            } catch (\Exception $e) {
                throw new \InvalidArgumentException(
                    sprintf('The property "$%s" does not exist on class "%s"', $propertyName, get_class($object))
                );
            }
        }
    }
}
