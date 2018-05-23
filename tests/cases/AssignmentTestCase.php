<?php

class AssignmentTestCase extends PHPUnit\Framework\TestCase
{
    public function getProtectedMethod($methodName, $class)
    {
       $classReflector = new ReflectionClass($class);
       $methodReflector = $classReflector->getMethod($methodName);
       $methodReflector->setAccessible(true);
       return $methodReflector;
    }

    public function getProtectedProperty($propertyName, $class)
    {
       $propertyReflector = new ReflectionProperty($class, $propertyName);
       $propertyReflector->setAccessible(true);
       return $propertyReflector;
    }
}