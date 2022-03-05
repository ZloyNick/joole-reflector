<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/ReflectedObject2.php';
require_once __DIR__.'/ExampleClass.php';

use joole\reflector\property\PropertyInterface;
use PHPUnit\Framework\TestCase;
use joole\reflector\Reflector;

class ReflectionsTest extends TestCase
{

    private object $object;
    private Reflector $reflector;

    public function __construct()
    {
        parent::__construct();

        $this->reflector = $reflector = new Reflector();
        $this->object = $reflector->buildFromObject(new ExampleClass());
    }

    /**
     * A simple reflections test
     */
    public function testReflectionConstruction(){
        $object = &$this->object;
        $this->assertNotNull($object->getProperty('privateStringProperty'));
    }

    public function testVariablesValues(){
        $object = &$this->object;

        $this->assertStringContainsString('Test case!', $object->getProperty('privateStringProperty')->getValue());
        $this->assertTrue(!array_diff(['a', 'c', 'm'], $object->getProperty('protectedArrayProperty')->getValue()));
        $this->assertEquals(.0987, $object->getProperty('publicFloatProperty')->getValue());
    }

    public function testValChange(){
        $object = &$this->object;

        $prop = $object->getProperty('privateStringProperty');
        $prop->setValue('Test string');

        $this->assertEquals('Test string', $prop->getValue());

        $prop = $object->getProperty('protectedArrayProperty');
        $prop->setValue([]);

        $this->assertEquals([], $prop->getValue());

        $prop = $object->getProperty('publicFloatProperty');
        $prop->setValue(.0987);

        $this->assertEquals(.0987, $prop->getValue());

        $prop->setValue(null);
        $this->assertNull($prop->getValue());

        $this->assertNull($object->getObject()->publicFloatProperty);
    }

    public function testMergeOptions(){
        $class = $this->reflector->merge(ExampleClass::class, [

            'privateStringProperty' => 'Merged param!',
            'protectedArrayProperty' => ['merged', 'array'],
            'publicFloatProperty' => 9.999,
        ]);

        $reflectedClass = $this->reflector->buildFromObject($class);

        $this->assertEquals('Merged param!', $reflectedClass->getProperty('privateStringProperty')->getValue());
        $this->assertTrue(!array_diff(['merged', 'array'], $reflectedClass->getProperty('protectedArrayProperty')->getValue()));
        $this->assertEquals(9.999, $reflectedClass->getProperty('publicFloatProperty')->getValue());
    }

    public function testObjectMerge(){
        $from = new ExampleClass();
        $reflectedFrom = $this->reflector->buildFromObject($from);

        $reflectedFrom->getProperty('privateStringProperty')->setValue('Test string');
        $reflectedFrom->getProperty('publicFloatProperty')->setValue(.0987);


        $class = $this->reflector->merge(ExampleClass::class, $reflectedFrom->getObject(), [
            'privateStringProperty',
            'protectedArrayProperty' => ['merged', 'array'],
            'publicFloatProperty',
        ]);

        $reflectedClass = $this->reflector->buildFromObject($class);

        $this->assertEquals('Test string', $reflectedClass->getProperty('privateStringProperty')->getValue());
        $this->assertTrue(!array_diff(['merged', 'array'], $reflectedClass->getProperty('protectedArrayProperty')->getValue()));
        $this->assertEquals(.0987, $reflectedClass->getProperty('publicFloatProperty')->getValue());
    }

    public function testReflectedObjectClassChange(){
        $newClass = ReflectedObject2::class;

        try{
            Reflector::setReflectedObjectClass($newClass);
        }catch (Exception $exception){

        }

        $this->assertStringContainsString($newClass, Reflector::getReflectedObjectClass());
    }

    public function testReflectedObjectClassChangeInvalid(){
        $this->expectException(InvalidArgumentException::class);
        $newClass = ExampleClass::class;
        Reflector::setReflectedObjectClass($newClass);
    }

    public function testVisibility(){
        $this->assertTrue(
            $this->object->getProperty('protectedArrayProperty')->getVisibility(false) !== PropertyInterface::T_PRIVATE
            && $this->object->getProperty('protectedArrayProperty')->getVisibility(false) === PropertyInterface::T_PROTECTED
        );
        $this->assertTrue($this->object->getProperty('privateStringProperty')->getVisibility(false) === PropertyInterface::T_PRIVATE);
        $this->assertTrue($this->object->getProperty('privateStringProperty')->getVisibility() === PropertyInterface::NAME_T_PRIVATE);
    }

}