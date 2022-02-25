<?php

declare(strict_types=1);

namespace joole\reflector\object;

use joole\reflector\property\PropertyInterface;

/**
 * The ReflectedObjectInterface is helper for reflected objects.
 */
interface ReflectedObjectInterface
{

    public function getProperties():array;

    public function getProperty(string $name):?PropertyInterface;

    public function getClassName():string;
}