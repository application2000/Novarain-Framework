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
            'getExecuter',
            'getURI'
        ];

        $factoryStub = $this->getMockBuilder('\\NRFramework\\Factory')
            ->setMethods($methods)
            ->getMock();

        // Mock getURI
        JUri::reset();

        $_SERVER['HTTP_HOST'] = 'www.example.com:80';
		$_SERVER['SCRIPT_NAME'] = '/joomla/index.php';
		$_SERVER['PHP_SELF'] = '/joomla/index.php';
		$_SERVER['REQUEST_URI'] = '/joomla/index.php?var=value 10';

        $uri = new JURI();
        $factoryStub->method('getURI')->willReturn($uri);

        // Mock getUser
        $user = new JUser;
        $user->id       = 15;
        $user->name     = 'John Doe';
        $user->username = 'johndoe';

        $factoryStub->method('getUser')->willReturn($user);

        $this->factoryStub = $factoryStub;
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