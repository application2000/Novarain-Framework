<?php

include_once __DIR__ . '/../cases/AssignmentTestCase.php';

use \NRFramework\Assignments\Time;
use \NRFramework\Factory;

class TimeTest extends AssignmentTestCase
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
        // timezone, up, down, now, expected
        return [
            // Simple Check
            ['+0300', '17:00', '19:00', '2018-05-01 15:00', true, true],
            ['+0300', '14:00', '16:00', '2018-05-01 15:00', false, true],
            ['+0300', '15:10', '16:00', '2018-05-01 15:00', false, false],
            ['-0300', '12:00', '12:30', '2018-05-01 15:00', true, true],
            ['-0300', '14:50', '15:30', '2018-05-01 15:00', false, true],
            ['-0300', '15:10', '16:00', '2018-05-01 15:00', false, false],

            // Midnight hell
            ['+0300', '00:00', '00:30', '2018-05-01 23:00', false, false],
            ['+0300', '00:00', '00:30', '2018-05-01 00:01', false, true],
            ['+0100', '00:00', '01:00', '2018-05-01 00:01', false, true],
            ['+0100', '00:00', '01:00', '2018-05-01 00:01', true, false],
            ['+0100', '00:00', '01:00', '2018-05-01 01:01', true, false],  

            // Negative offset -0500
            ['America/Jamaica', '00:02', '01:02', '2018-05-03 21:00', true, false],
            ['America/Jamaica', '15:00', '16:30', '2018-05-03 21:00', true, true],
            ['America/Jamaica', '20:30', '21:30', '2018-05-03 21:00', false, true],
            ['America/Jamaica', '20:00', '21:30', '2018-05-03 02:00', true, true],
        ];
    }

    /**
     * @dataProvider passDataProvider
     */
    public function testTimePass($tz, $up, $down, $now, $modify_offset, $expected)
    {
        $this->options->params = (object) [
            'timezone'      => $tz,
            'publish_up'    => $up,
            'publish_down'  => $down,
            'now'           => $now,
            'modify_offset' => $modify_offset
        ];

        $time_assignment = new Time($this->options, $this->factoryStub);

        $this->assertEquals($expected, $time_assignment->pass());
    }
}