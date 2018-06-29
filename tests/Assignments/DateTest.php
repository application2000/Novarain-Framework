<?php

include_once __DIR__ . '/../cases/AssignmentTestCase.php';

use \NRFramework\Assignments\Date;
use \NRFramework\Factory;

class DateTest extends AssignmentTestCase
{
    public function setUp()
    {
        $this->options = (object) [
            'params' => (object) ['timezone' => '+0000'],
            'selection' => null,
            'assignment_state' => null
        ];

        $this->factoryStub = $this->getMockBuilder('\\NRFramework\\Factory')
            ->setMethods(['getDate', 'getDbo', 'getUser', 'getApplication', 'getDocument'])
            ->getMock();
    }

    public function passDataProvider()
    {
        // timezone, up, down, now, expected
        return [
            ['+0000', '2018-6-1', '2018-7-1', '2018-6-15', true],
            ['+0000', '2018-6-1 00:15', '2018-6-1 00:20', '2018-6-1 00:17', true],
            ['+0000', '2018-6-1 00:15:10', '2018-6-1 00:15:12', '2018-6-1 00:15:11', true],
            ['+0000', '2018-6-1 00:15:10', '2018-6-1 00:15:12', '2018-6-1 00:15:10', true],
            ['+0000', '2018-6-1 00:15', '2018-6-1 01:15', '2018-6-1 00:30', true],
            ['+0000', '2018-6-1 00:0', '2018-6-2 00:00', '2018-6-2 01:00', false],
            ['+0000', '2018-6-1 00:0', null, '2018-6-2 01:00', true],
            ['+0000', null, '2018-6-2 00:00', '2018-6-1 15:00', true],
            ['+0000', null, '2018-6-2 00:00', '2018-6-2 01:00', false],
            ['+0000', '2018-6-2 00:00', null, '2018-6-2 01:00', true],
            ['+0000', null, null, '2018-6-2 01:00', false],

            ['+0100', '2018-6-1 00:15', '2018-6-1 02:15', '2018-6-1 01:00', true],
            ['+0200', '2018-6-1 00:15', '2018-6-1 02:15', '2018-6-1 01:00', true],
            ['Europe/Athens', '2019-1-1 00:15', '2019-1-1 02:15', '2019-1-1 01:00', true],
            // ['+0000', '2018-6-2 00:00', null, null, true], --> this data set depends on external factors (current time) and cannot be tested
        ];
    }

    public function valueDataProvider()
    {
        // now
        return [
            ['2018-6-15'],
            ['2016-10-2'],
            ['2019-1-1 00:00'],
            ['2019-1-1 00:00:00'],
            [null],
        ];
    }

    /**
     * @dataProvider passDataProvider
     */
    public function testDatePass($tz, $up, $down, $now, $expected)
    {
        $this->options->params = (object) [
            'timezone'      => $tz,
            'publish_up'    => $up,
            'publish_down'  => $down,
        ];

        $this->factoryStub->method('getDate')->willReturn(new \JDate($now));
        
        $date_assignment = new Date($this->options, $this->factoryStub);
        $this->assertEquals($expected, $date_assignment->pass());
    }

    /**
     * @dataProvider valueDataProvider
     */
    public function testValue($now)
    {
        $now_date = new \JDate($now);

        $this->factoryStub->method('getDate')->willReturn($now_date);
        
        $date_assignment = new Date($this->options, $this->factoryStub);

        $this->assertEquals($date_assignment->value(), $now_date);
    }
}