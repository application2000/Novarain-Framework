<?php

class TestCase extends PHPUnit\Framework\TestCase
{
    public function setUp()
    {
        $methods = [
            'getDbo',
            'getUser',
            'getApplication',
            'getDocument',
            'getExecuter'
        ];

        $this->factoryStub = $this->getMockBuilder('\\NRFramework\\Factory')
            ->setMethods($methods)
            ->getMock();
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}