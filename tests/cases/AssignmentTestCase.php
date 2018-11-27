<?php

class AssignmentTestCase extends PHPUnit\Framework\TestCase
{
    // common setup for assignment testing
    public function setUp()
    {
        $this->options = (object) [
            'params' => null,
            'selection' => null,
            'assignment_state' => null
        ];

        $this->factoryStub = $this->getMockBuilder('\\NRFramework\\Factory')
            ->setMethods(['getDbo', 'getUser', 'getApplication', 'getDocument'])
            ->getMock();
    }

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