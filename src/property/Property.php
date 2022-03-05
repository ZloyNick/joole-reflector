<?php

declare(strict_types=1);

namespace joole\reflector\property;

use joole\reflector\object\ReflectedObjectInterface;
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

    private ReflectedObjectInterface $owner;

    public function __construct(ReflectionProperty $property, ReflectedObjectInterface $owner)
    {
        $this->property = $property;
        $this->owner = $owner;
    }

    public function setAccessible(bool $value): void
    {
        $this->property->setAccessible($value);
    }

    /**
     * @throws ReflectionException
     */
    public function setValue(mixed $value): void
    {
        $property = $this->property;
        $val = $this->getValue();
        $type = $property->getType();

        if(!is_null($type)){
            if(is_null($value)){
                if(!$type->allowsNull()){
                    throw new ReflectionException('Null not allowed for property' . $property->getName());
                }
            }else{
                $propertyType = $type->getName();
                $type = gettype($value);

                if ($propertyType !== $type) {
                    if($propertyType !== 'float' && !in_array($type, ['float', 'real', 'double'])){
                        throw new ReflectionException('Value must be instance of ' . $propertyType);
                    }
                }
            }
        }

        $property->setValue($this->owner->getObject(), $value);
    }

    public function getValue(): mixed
    {
        $prop = $this->property;

        if($prop->isStatic()){
            return $prop->getValue();
        }

        return $prop->getValue($this->owner->getObject());
    }
}