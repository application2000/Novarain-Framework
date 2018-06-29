<?php

include_once __DIR__ . '/../cases/AssignmentTestCase.php';

use \NRFramework\Assignments\Month;
use \NRFramework\Factory;

class MonthTest extends AssignmentTestCase
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
            [[1,2,3], '2018-1-1', true],
            [[12, 10], '2018-4-1', false],
            [[12, null, ''], '2018-12-1', true],
            [['January', 'February'], '2018-1-1', true],
            [['JANUARY', 'FEBRUARY'], '2018-1-1', true],
            [['APRIL', 'Feb'], '2018-1-1', false],
            [['JUNE', '', null], '2018-6-1', true],
            [['Mar'], '2018-3-1', true],
            [['Nov', 'Dec'], '2020-11-1', true],
            [null, '2018-1-1', false],
            [[], '2018-1-1', false],
            [[''], '2018-1-1', false]
        ];
    }

    /**
     * @dataProvider passDataProvider
     */
    public function testMonthPass($selection, $now, $expected)
    {
        $this->options->selection = $selection;
        $this->factoryStub->method('getDate')->willReturn(new \JDate($now));
        
        $month_assignment = new Month($this->options, $this->factoryStub);
        $this->assertEquals($expected, $month_assignment->pass());
    }
}