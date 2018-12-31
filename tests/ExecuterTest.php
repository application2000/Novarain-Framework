<?php

include_once __DIR__ . '/cases/TestCase.php';

use \NRFramework\Executer;

class ExecuterTest extends TestCase
{
    private $executer;

    public function setUp()
    {   
        // Mock Executer and disable getFunctionVariables
        $executer = $this->getMockBuilder("\\NRFramework\\Executer")
            ->setMethods(['getFunctionVariables'])
            ->getMock();

        $executer->method('getFunctionVariables')->willReturn([]);

        $this->executer = $executer;
    }

    public function runData()
    {
        return [
            ['', true],
            ['$x = "true"; return $x;', 'true'],
            ['$x = false; return $x;', false]
        ];
    }

    /**
     * @dataProvider runData
     */
    public function testRun($php, $expected)
    {
        $result = $this->executer->setPhpCode($php)->run();

        $this->assertEquals($result, $expected);
    }

    /**
     * Test executer' temp path
     *
     * @return void
     */
    public function testGetTempPath()
    {
        // Invoke private method
        $path   = $this->invokeMethod($this->executer, 'getTempPath');
        $exists = JFolder::exists($path);
        
        $this->assertTrue($exists);
    }

    public function testGetFunctionContent()
    {
        $content = $this->invokeMethod($this->executer, 'getFunctionContent');

        $this->assertContains('function tassos_php', $content);
    }

    public function testCreateFunction()
    {
        $this->executer->setPhpCode('return ("John Doe")');

        $function_exists = $this->invokeMethod($this->executer, 'createFunction');

        $this->assertTrue($function_exists);
    }
}