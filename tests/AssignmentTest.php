<?php

use \NRFramework\Assignment;

class AssignmentTest extends PHPUnit\Framework\TestCase
{
    public $assignment;

    public $factoryStub;

    public function setUp()
    {
        $this->factoryStub = $this->getMockBuilder('\\NRFramework\\Factory')->getMock();
        $this->factoryStub->method('getDbo')->willReturn(null);
        $this->factoryStub->method('getUser')->willReturn(null);

        $options = (object) [
            'params' => null,
            'selection' => null,
            'assignment_state' => null
        ];

        $this->assignment = new Assignment($options, null, null, $this->factoryStub);
    }

    public function passSimpleProvider()
    {
        return [
            [null, null, false],
            ['desktop', 'DESKTOP', true]
        ];
    }
   

    /**
     * @dataProvider passSimpleProvider
     */
    public function testPassSimple($values, $selection, $expected)
    {
        $this->assertEquals($this->assignment->passSimple($values, $selection));
    }
}