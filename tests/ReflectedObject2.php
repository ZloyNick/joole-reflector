<?php

declare(strict_types=1);

use joole\reflector\property\PropertyInterface;

/**
 * Class ReflectedObject2
 *
 * An example for checking Reflector::setReflectedObjectClass();
 */
class ReflectedObject2 implements \joole\reflector\object\ReflectedObjectInterface
{

    public function getProperties(): array
    {
        return [];
    }

    public function getProperty(string $name): ?PropertyInterface
    {
        return null;
    }

    public function getClassName(): string
    {
        return '';
    }
}