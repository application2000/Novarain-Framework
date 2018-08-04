<?php

include_once __DIR__ . '/../cases/AssignmentTestCase.php';

use \NRFramework\Assignments\City;
use \NRFramework\Factory;

class CityTest extends AssignmentTestCase
{
    public function passData()
    {
        //ip, selection, expected
        return [
            ['185.225.105.75', '', false],
            ['185.225.105.75', null, false],
            ['185.225.105.75', 'Athens', true],
            ['49.126.92.10', 'Patan', true],
            ['128.250.204.118', 'Melbourne', true],
            ['217.129.2.18', 'Lisbon', true],
            ['217.129.2.18', 'Portugal', false],
            ['113.255.29.133', 'Central', true],  //Hong Kong
            ['206.104.212.254', 'Plymouth', true], //California
            ['185.225.105.75', ['Athens'], true],
            ['185.225.105.75', ['Athens', 'Lisbon'], true],
            ['185.225.105.75', ['Athens', ''], true],
            ['185.225.105.75', ['', ''], false],
            ['185.225.105.75', [null], false],
            ['185.225.105.75', [null], false],
            ['113.255.29.133', ['Athens', 'Lisbon'], false],
        ];
    }

    /**
     * @dataProvider passData
     */
    public function testPass($ip, $selection, $expected)
    {
        $this->options->params = (object) [ 'ip' => $ip];
        $this->options->selection = $selection;

        $city = new City($this->options, $this->factoryStub);

        $this->assertEquals($expected, $city->pass());
    }
}