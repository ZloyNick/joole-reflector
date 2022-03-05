<?php

declare(strict_types=1);

namespace joole\reflector\object;

use joole\reflector\property\Property;
use joole\reflector\property\PropertyInterface;
use ReflectionClass;

class ReflectedObject implements ReflectedObjectInterface
{

    /**
     * Reflected object.
     *
     * @var ReflectionClass
     */
    private ReflectionClass $reflectedObject;

    /**
     * The given object.
     *
     * @var object
     */
    private object $object;

    /**
     * Properties.
     *
     * @var PropertyInterface[]
     */
    private array $properties = [];

    /**
     * ReflectedObject constructor.
     * @param object $object
     */
    public function __construct(object $object)
    {
        $this->object = $object;
        $this->reflectedObject = new ReflectionClass($object);

        $this->init();
    }

    /**
     * Returns given object.
     *
     * @return object
     */
    public function getObject() : object{
        return $this->object;
    }

    /**
     * @return Property[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    public function getProperty(string $name): null|Property|PropertyInterface
    {
        return $this->properties[$name] ?? null;
    }

    /**
     * Returns reflected object's name.
     *
     * @return string
     */
    public function getClassName(): string
    {
        return $this->reflectedObject->getName();
    }

    /**
     * Initializes properties.
     *
     * @return void
     */
    protected function init(): void
    {
        $reflectedObject = $this->reflectedObject;
        $object = &$this->object;
        $properties = &$this->properties;

        foreach ($reflectedObject->getProperties() as $property){
            $properties[$property->getName()] = new Property($property, $object);
        }
    }
}