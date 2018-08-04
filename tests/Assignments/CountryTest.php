<?php

include_once __DIR__ . '/../cases/AssignmentTestCase.php';

use \NRFramework\Assignments\Country;
use \NRFramework\Factory;

class CountryTest extends AssignmentTestCase
{
    public function passData()
    {
        //ip, selection, expected
        return [
            ['185.225.105.75', '', false],
            ['185.225.105.75', null, false],
            ['185.225.105.75', 'GR', true],
            ['49.126.92.10', 'NP', true], //Nepal
            ['128.250.204.118', 'AU', true], //Australia
            ['217.129.2.18', 'PT', true], //Portugal
            ['217.129.2.18', 'NP', false],
            ['113.255.29.133', 'HK', true],  //Hong Kong
            ['206.104.212.254', 'US', true], //California
            ['185.225.105.75', ['GR'], true],
            ['185.225.105.75', ['GR', 'AU'], true],
            ['185.225.105.75', ['GR', ''], true],
            ['185.225.105.75', ['', ''], false],
            ['185.225.105.75', [null], false],
            ['185.225.105.75', [null], false],
            ['113.255.29.133', ['GR', 'US'], false],
        ];
    }

    /**
     * @dataProvider passData
     */
    public function testPass($ip, $selection, $expected)
    {
        $this->options->params = (object) [ 'ip' => $ip];
        $this->options->selection = $selection;

        $country = new Country($this->options, $this->factoryStub);
       
        $this->assertEquals($expected, $country->pass());
    }
}