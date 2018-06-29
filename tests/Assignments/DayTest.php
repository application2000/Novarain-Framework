<?php

include_once __DIR__ . '/../cases/AssignmentTestCase.php';

use \NRFramework\Assignments\Day;
use \NRFramework\Factory;

class DayTest extends AssignmentTestCase
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
        return [
            [['Monday'], '2018-6-4', true],
            [['Mon'], '2018-6-4', true],
            [['TUESDAY'], '2018-6-5', true],
            [['Wed'], '2018-6-6', true],
            [['weekdays'], '2018-6-7', true],
            [['weekend'], '2018-6-16', true],
            [['WEEKEND', null], '2018-6-17', true],
            [[5], '2018-6-1', true],
            [[1, 2, 3], '2018-6-1', false],
            [null, '2018-1-1', false],
            [[], '2018-1-1', false],
            [[''], '2018-1-1', false]
        ];
    }

    /**
     * @dataProvider passDataProvider
     */
    public function testDayPass($selection, $now, $expected)
    {
        $this->options->selection = $selection;
        $this->factoryStub->method('getDate')->willReturn(new \JDate($now));
        
        $day_assignment = new Day($this->options, $this->factoryStub);
        $this->assertEquals($expected, $day_assignment->pass());
    }
}