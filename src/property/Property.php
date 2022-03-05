<?php

declare(strict_types=1);

namespace joole\reflector\property;

use ReflectionException;
use ReflectionProperty;
use function is_null;

/**
 * Class Property
 */
final class Property implements PropertyInterface
{

    /**
     * ReflectionProperty
     *
     * @var ReflectionProperty
     */
    private ReflectionProperty $property;

    /**
     * An object, that owns property.
     *
     * @var object
     */
    private object $owner;

    public function __construct(ReflectionProperty $property, object $owner)
    {
        $this->property = $property;
        $this->owner = $owner;
    }

    /**
     * @throws ReflectionException
     */
    public function setValue(mixed $value): void
    {
        $property = $this->property;
        $type = $property->getType();

        if (!is_null($type)) {
            if (is_null($value)) {
                if (!$type->allowsNull()) {
                    throw new ReflectionException('Null not allowed for property' . $property->getName());
                }
            } else {
                $propertyType = $type->getName();
                $type = gettype($value);

                if ($propertyType !== $type) {
                    if ($propertyType !== 'float' && !in_array($type, ['float', 'real', 'double'])) {
                        throw new ReflectionException('Value must be instance of ' . $propertyType);
                    }
                }
            }
        }

        $property->setValue($this->owner, $value);
    }

    /**
     * Returns property's value.
     *
     * @return mixed If no value is set, returns $default,
     * otherwise returns the default value or null (if no default value is set)
     */
    public function getValue(mixed $default = null): mixed
    {
        if(!$this->haveValue()){
            return $default ?? $this->getDefaultValue();
        }

        $prop = $this->property;

        if ($prop->isStatic()) {
            return $prop->getValue();
        }

        return $prop->getValue($this->owner);
    }

    public function getVisibility(bool $asString = true): string|int
    {
        $property = $this->property;

        if ($asString) {
            return $property->isPublic() ? self::NAME_T_PUBLIC :
                ($property->isProtected() ? self::NAME_T_PROTECTED : self::NAME_T_PRIVATE);
        }

        return $property->isPublic() ? self::T_PUBLIC :
            ($property->isProtected() ? self::T_PROTECTED : self::T_PRIVATE);
    }

    /**
     * Checks if the property has a value.
     *
     * @return bool
     */
    final public function haveValue(): bool
    {
        return $this->property->isPromoted();
    }

    /**
     * Returns property as reflected by php.
     *
     * @return ReflectionProperty
     */
    final public function getProperty(): ReflectionProperty
    {
        return $this->property;
    }

    /**
     * Returns default value of property.
     *
     * @return mixed Returns null if it has been set or if property hasn't default value.
     */
    final public function getDefaultValue():mixed{
        return $this->property->getDefaultValue();
    }

    /**
     * Checks if the property has a default value.
     *
     * @return bool
     */
    final public function hasDefaultValue():bool{
        return $this->property->hasDefaultValue();
    }
}