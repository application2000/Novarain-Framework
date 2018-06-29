<?php

// include_once __DIR__ . '/../cases/AssignmentTestCase.php';

// use \NRFramework\Assignments\Time;
// use \NRFramework\Factory;

// class TimeTest extends AssignmentTestCase
// {
//     public function setUp()
//     {
//         $this->options = (object) [
//             'params' => (object) ['timezone' => '+0000'],
//             'selection' => null,
//             'assignment_state' => null
//         ];

//         $this->factoryStub = $this->getMockBuilder('\\NRFramework\\Factory')
//             ->setMethods(['getDate', 'getDbo', 'getUser', 'getApplication', 'getDocument'])
//             ->getMock();
//     }

//     public function passDataProvider()
//     {
//         // timezone, up, down, now, expected
//         return [
//             ['+0000', '00:00', '01:00', '2018-6-10 00:30', true],
//             ['+0000', '00:00', '00:02', '2018-6-10 00:01', true],
//             ['+0100', '00:00', '01:00', '2018-6-10 00:30', true],
//             ['+0200', '15:00', '16:00', '2018-6-10 15:30', true],
//             ['+0300', '15:00', '17:00', '2018-6-10 16:00', true],
//             ['+0000', '00:00', '01:00', '2018-6-10 01:30', false],
//             ['+0100', '00:00', '01:00', '2018-6-10 01:30', false],
//             ['+0200', '15:00', '17:00', '2018-6-10 17:30', false]
//         ];
//     }

//     /**
//      * @dataProvider passDataProvider
//      */
//     public function testTimePass($tz, $up, $down, $now, $expected)
//     {
//         $this->options->params = (object) [
//             'timezone'      => $tz,
//             'publish_up'    => $up,
//             'publish_down'  => $down,
//         ];
        
//         $this->factoryStub->method('getDate')->willReturn(new \JDate($now));
        
//         $time_assignment = new Time($this->options, $this->factoryStub);
//         $this->assertEquals($expected, $time_assignment->pass());
//     }
// }