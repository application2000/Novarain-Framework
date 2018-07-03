<?php

include_once __DIR__ . '/../cases/AssignmentTestCase.php';

use \NRFramework\Assignments\Date;
use \NRFramework\Factory;

class DateTest extends AssignmentTestCase
{
    public function setUp()
    {
        $this->options = (object) [
            //'params' => (object) ['timezone' => '+0000'],
            'selection' => null,
            'assignment_state' => null
        ];

        $this->factoryStub = $this->getMockBuilder('\\NRFramework\\Factory')
            ->setMethods(['getDbo', 'getUser', 'getApplication', 'getDocument'])
            ->getMock();
    }

    public function passDataProvider()
    {
        // timezone, up, down, now, modify_offset, expected
        return [
            ['America/New_York', '2018-6-1', '2018-7-1', '2018-6-15', true, true],                           // Simple data range
            ['+1', '2018-6-1 00:15:10', '2018-6-1 00:15:12', '2018-6-1 00:15:11', false, true],              // Check a second after
            ['+2', '2018-6-1 00:15:10', '2018-6-1 00:15:12', '2018-6-1 00:15:10', true, true],               // Now date is equal to up
            ['+2', '2018-6-1 00:15', '2018-6-1 00:15', '2018-6-1 00:15', true, true],                        // Now date is equal to down
            ['+3', '2018-6-1 00:15', '2018-6-1 01:15', '2018-6-1 00:30', true, true],
            ['+4', '2018-6-1 00:0', '2018-6-2 00:00', '2018-6-2 01:00', false, false],
            ['Europe/Berlin', '2018-6-1 00:0', null, '2018-6-2 01:00', true, true],                           
            ['-10', null, '2018-6-2 00:00', '2018-6-2 01:00', false, false],
            ['-3', '2018-6-2 00:00', null, '2018-6-2 01:00', true, true],
            ['-4', null, null, '2018-6-2 01:00', true, false],
            ['+2', '2018-6-1 00:15', '2018-6-1 02:15','2018-6-1 01:00', true, true],
            ['+2', '2018-6-1 00:15', '2018-6-1 02:15','2018-6-1 01:00', false, true],
            ['Europe/Athens', '2019-1-01 00:15:00', '2019-1-1 02:15', '2019-1-1 01:02', true, true],
            ['Europe/Athens', '2019-1-01 00:15:00', '2019-1-01 00:16:00', '2019-1-1 00:15:01', true, true],
        ];
    }

    /*public function valueDataProvider()
    {
        // now
        return [
            ['2018-6-15'],
            ['2016-10-2'],
            ['2019-1-1 00:00'],
            ['2019-1-1 00:00:00'],
            [null],
        ];
    }*/

    /**
     * @dataProvider passDataProvider
     */
    public function testDatePass($tz, $up, $down, $now, $modify_offset, $expected)
    {
        $this->options->params = (object) [
            'timezone'      => $tz,
            'publish_up'    => $up,
            'publish_down'  => $down,
            'now'           => $now,
            'modify_offset' => $modify_offset
        ];

        $date_assignment = new Date($this->options, $this->factoryStub);
        $this->assertEquals($expected, $date_assignment->pass());
    }

    /**
     * @dataProvider valueDataProvider
     */
    /*public function testValue($now)
    {
        $now_date = new \JDate($now);
        $this->factoryStub->method('getDate')->willReturn($now_date);
        
        $date_assignment = new Date($this->options, $this->factoryStub);

        $this->assertEquals($date_assignment->value(), $now);
    }*/
}