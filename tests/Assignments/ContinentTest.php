<?php

include_once __DIR__ . '/../cases/AssignmentTestCase.php';

use \NRFramework\Assignments\Continent;
use \NRFramework\Factory;

class ContinentTest extends AssignmentTestCase
{
    public function passData()
    {
        //ip, selection, expected
        return [
            ['185.225.105.75', '', false],
            ['185.225.105.75', null, false],
            ['185.225.105.75', 'EU', true], // GR
            ['185.225.105.75', 'Europe', true], // GR
            ['49.126.92.10', 'AS', true], // Nepal
            ['49.126.92.10', 'Asia', true], // Nepal
            ['128.250.204.118', 'OC', true], // Australia
            ['128.250.204.118', 'Oceania', true], // Australia
            ['217.129.2.18', 'EU', true], // Portugal
            ['113.255.29.133', 'AS', true],  //Hong Kong
            ['206.104.212.254', 'NA', true], //USA
            ['206.104.212.254', 'North America', true], //USA
            ['197.232.2.245', 'AF', true], // Kenya
            ['197.232.2.245', 'Africa', true], // Kenya
            ['185.225.105.75', ['EU'], true],
            ['185.225.105.75', ['EU', 'AS'], true],
            ['185.225.105.75', "Europe, Asia", true],
            ['185.225.105.75', ['EU', ''], true],
            ['185.225.105.75', ['', ''], false],
            ['185.225.105.75', [null], false],
            ['185.225.105.75', [null], false],
            ['113.255.29.133', ['North America', 'Africa', null], false],
        ];
    }

    /**
     * @dataProvider passData
     */
    public function testPass($ip, $selection, $expected)
    {
        $this->options->params = (object) [ 'ip' => $ip];
        $this->options->selection = $selection;

        $continent= new Continent($this->options, $this->factoryStub);
        
        $this->assertEquals($expected, $continent->pass());
    }
}