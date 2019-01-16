<?php

include_once __DIR__ . '/../cases/AssignmentTestCase.php';

use \NRFramework\Assignments\Region;
use \NRFramework\Factory;

class RegionTest extends AssignmentTestCase
{
    public function passData()
    {
        //ip, selection, expected
        return [
            ['185.225.105.75', '', false],
            ['185.225.105.75', null, false],
            ['185.225.105.75', 'GR-I', true], // GR - Attiki
            ['128.250.204.118', 'AU-VIC', true], // Australia - Victoria
            ['217.129.2.18', 'PT-10', false],
            ['206.104.212.254', 'US-CA', true], //USA - California
            ['185.225.105.75', ['GR-I'], true],
            ['185.225.105.75', ['GR-I', 'PT-11'], true],
            ['185.225.105.75', "GR-I, PT-11", true],
            ['185.225.105.75', ['GR-I', ''], true],
            ['185.225.105.75', ['', ''], false],
            ['185.225.105.75', [null], false],
            ['185.225.105.75', [null], false],
            ['113.255.29.133', ['US-CA', 'NP-BA'], false],
        ];
    }

    /**
     * @dataProvider passData
     */
    public function testPass($ip, $selection, $expected)
    {
        $this->options->params = (object) [ 'ip' => $ip];
        $this->options->selection = $selection;

        $region= new Region($this->options, $this->factoryStub);

        $this->assertEquals($expected, $region->pass());
    }
}