<?php

include_once __DIR__ . '/../cases/AssignmentTestCase.php';

use \NRFramework\Assignments\URLBase;
use \NRFramework\Factory;

class URLBaseTest extends AssignmentTestCase
{
    public function passData()
    {
        return [
            ['', null, false, false],
            ['http://www.google.com', null, false, false],
            ['http://www.google.com', '', true, false],
            ['http://www.google.com', [], false, false],
            ['http://www.google.com', 'google.com', true, true],
            ['http://www.google.com', ['google.com'], false, true],
            ['http://www.google.com', ['yahoo.com', 'google.com'], false, true],
            ['http://www.google.com', 'google.gr', false, false],
            ['http://www.google.gr', '\.(com|gr)$', true, true],
            ['http://www.google.gr?test=1', '\?test=1', true, true]
        ];
    }

    /**
     * @dataProvider passData
     */
    public function testPass($browser_url, $selection, $regex = false, $expected)
    {
        $this->options->selection = $selection;
        $this->options->params = (object) ['regex' => $regex];
        
        $assignment = new URLBase($this->options, $this->factoryStub);

        $this->assertEquals($expected, $assignment->passURL($browser_url));
    }
}