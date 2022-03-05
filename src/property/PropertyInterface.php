<?php

declare(strict_types=1);

namespace joole\reflector\property;

/**
 * Reflected property interface helper
 */
interface PropertyInterface
{

    /**
     * A property visibilities as integer.
     */
    public const T_PUBLIC = 0;
    public const T_PROTECTED = 1;
    public const T_PRIVATE = 2;

    /**
     * A property visibilities as string.
     */
    public const NAME_T_PUBLIC = 'public';
    public const NAME_T_PROTECTED = 'protected';
    public const NAME_T_PRIVATE = 'private';

    /**
     * Returns property's visibility.
     *
     * @param bool $asString If contains true, returns visibility as string,
     * else returns it as int.
     * @see PropertyInterface::NAME_T_PROTECTED, PropertyInterface::NAME_T_PROTECTED, PropertyInterface::NAME_T_PROTECTED
     * @see PropertyInterface::T_PROTECTED, PropertyInterface::T_PROTECTED, PropertyInterface::T_PROTECTED
     */
    public function getVisibility(bool $asString = true): string|int;

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