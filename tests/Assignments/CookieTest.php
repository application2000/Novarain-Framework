<?php

include_once __DIR__ . '/../cases/AssignmentTestCase.php';

use \NRFramework\Assignments\Cookie;
use \NRFramework\Factory;

class CookieTest extends AssignmentTestCase
{
    public function passData()
    {
        // cookie_data, user_content, selection, expected
        return [
            ['4bt4m8ofb4tj5firn4r1af7g33', '', 'exists', true],
            ['4bt4m8ofb4tj5firn4r1af7g33', '4bt4m8ofb4tj5firn4r1af7g33', 'equal', true],
            ['', '4bt4m8ofb4tj5firn4r1af7g33', 'equal', false],
            ['4bt4m8ofb4tj5firn4r1af7g33', '4bt4m8ofb4tj5firn4r3', 'equal', false],
            ['4bt4m8ofb4tj5firn4r1af7g33', 'n4r1af7g33', 'contains', true],
            ['4bt4m8ofb4tj5firn4r1af7g33', '4bt4m8of', 'contains', true],
            ['4bt4m8ofb4tj5firn4r1af7g33', '8of', 'contains', true],
            ['4bt4m8ofb4tj5firn4r1af7g33', '', 'contains', false],
            ['4bt4m8ofb4tj5firn4r1af7g33', 'f7g33', 'ends', true],
            ['4bt4m8ofb4tj5firn4r1af7g33', '4bt4m8ofb', 'starts', true],
        ];
    }

    /**
     * @dataProvider passData
     */
    public function testPass($cookie_data, $user_content, $selection, $expected)
    {
        $this->options->selection = $selection;
        $this->options->params = (object) ['content' => $user_content];

        $this->cookie_assignment = $this->getMockBuilder('\\NRFramework\\Assignments\\Cookie')
            ->setConstructorArgs([$this->options, $this->factoryStub])
            ->setMethods(['value'])
            ->getMock();

        $this->cookie_assignment->method('value')->willReturn($cookie_data);

        $this->assertEquals($expected, $this->cookie_assignment->pass());
    }
}