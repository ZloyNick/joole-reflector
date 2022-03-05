<?php

declare(strict_types=1);

namespace joole\reflector\property;

/**
 * Reflected property interface helper
 */
interface PropertyInterface
{

    /**
     * Sets property accessible.
     *
     * @param true|false $value
     */
    public function setAccessible(bool $value): void;

    /**
     * Sets property's value.
     *
     * @param mixed|null $value
     */
    public function setValue(mixed $value): void;

    /**
     * Returns property's value.
     *
     * @return mixed
     */
    public function getValue(): mixed;

}