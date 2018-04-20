<?php

namespace SuperBrave\GdprBundle\Manipulator;

use ReflectionProperty;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

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
            $reflectionProperty = new ReflectionProperty($object, $propertyName);
            $reflectionProperty->setAccessible(true);

            $propertyData = $reflectionProperty->getValue($object);
        }

        return $propertyData;
    }

    /**
     * Sets a new value on the specified property
     *
     * @param object $object       The object containing the property
     * @param string $propertyName The property name where the value is written to
     * @param mixed  $value        The new value for the property
     */
    public function setPropertyValue($object, $propertyName, $value)
    {
        try {
            $this->propertyAccessor->setValue($object, $propertyName, $value);
        } catch (NoSuchPropertyException $exception) {
            $reflectionProperty = new ReflectionProperty($object, $propertyName);
            $reflectionProperty->setAccessible(true);

            $reflectionProperty->setValue($object, $value);
        }
    }
}
