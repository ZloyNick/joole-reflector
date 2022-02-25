<?php

declare(strict_types=1);

namespace joole\reflector;

use ErrorException;
use joole\reflector\object\ReflectedObject;
use joole\reflector\object\ReflectedObjectInterface;
use Prophecy\Exception\Doubler\ClassNotFoundException;
use ReflectionException;

use function is_string;
use function is_null;
use function is_array;
use function class_exists;
use function call_user_func_array;
use function is_int;
use function method_exists;
use function array_keys;

/**
 * Reflector is used to work with the properties of objects, their changes and merges.
 */
final class Reflector
{

    /**
     * The class to be used for reflection.
     *
     * @var string
     */
    private static string $reflectedObjectClass = ReflectedObject::class;

    /**
     * Generates reflected class.
     *
     * @param string|object $object
     * @param array $constructParams
     * @return ReflectedObjectInterface|ReflectedObject
     */
    public function buildFromObject(string|object $object, array $constructParams = []): ReflectedObjectInterface|ReflectedObject
    {
        if(is_string($object)){
            if(!class_exists($object)){
                throw new ClassNotFoundException('Class not found!', $object);
            }

            $object = method_exists($object, '__construct') ?
                call_user_func_array([$object, '__construct'], $constructParams)
                : new $object();
        }

        return new self::$reflectedObjectClass($object);
    }

    /**
     * @param string $class
     */
    public static function setReflectedObjectClass(string $class)
    {
        self::$reflectedObjectClass = $class;
    }

    /**
     * Merges properties from class to class.
     *
     * @param string|object $class Target class.
     * @param string|object|array $params Class or properties merge
     * @param array $params2 Properties of object $params, that will be added to $class.
     * If count of $params2 < 1 merges all properties from $params object.
     *
     * <code>
     *      ```
     *          $reflector = new Reflector();
     *
     *          $reflector->merge($class, [
     *              'id' => $id,
     *              'value' => $value,
     *          ]);
     *          // OR
     *          $reflector->merge($class, $classFrom, [
     *              'id' => $id,
     *              'value',//if exists at $classFrom
     *          ]);
     *      ```
     * <code>
     *
     * @return object
     * @throws ErrorException
     *
     * @throws ReflectionException
     */
    public function merge(string|object $class, string|object|array $params, array $params2 = []): object
    {
        $object = $this->buildFromObject($class);

        if (!is_array($params)) {
            $object2 = $this->buildFromObject($params);

            if(count($params2) === 0){
                $params2 = array_keys($object2->getProperties());
            }

            foreach ($params2 as $param => $v) {
                if (is_int($param)) {
                    $prop = $object->getProperty($v);
                    $prop2 = $object2->getProperty($v);

                    if (is_null($prop)) {
                        throw new ErrorException(
                            'Property $' . $v . ' of class ' . $object->getClassName() . ' doesn\'t exist!'
                        );
                    }

                    if (is_null($prop2)) {
                        throw new ErrorException(
                            'Property $' . $v . ' of class ' . $object2->getClassName() . ' doesn\'t exist!'
                        );
                    }

                    $prop->setValue($prop2->getValue());

                    continue;
                }

                $object->getProperty($param)->setValue($v);
            }
        }else{
            foreach ($params as $param => $v) {
                $prop = $object->getProperty($param);

                if (is_null($prop)) {
                    throw new ErrorException(
                        'Property $' . $param . ' of class ' . $object->getClassName() . ' doesn\'t exist!'
                    );
                }

                $prop->setValue($v);
            }
        }

        return $object->getObject();
    }

}