<?php

include_once __DIR__ . '/../cases/AssignmentTestCase.php';

use \NRFramework\Assignments\IP;
use \NRFramework\Factory;

class IPTest extends AssignmentTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->ip_assignment = new \NRFramework\Assignments\IP($this->options, $this->factoryStub);
    }

    public function prepareRangesData()
    {
        // ip_list, expected
        return [
            ['127.0.0.1', ['127.0.0.1']],
            ['127.0.0.1, 10.0.0.1', ['127.0.0.1', '10.0.0.1']],
            ['192.168.10', ['192.168.10']],
            ['192.168.10, 10.0.0.1-100', ['192.168.10', '10.0.0.1-100']],
            ['192.168.10, ', ['192.168.10']],
            ['192.168, ,,,', ['192.168']],
            ['192.168, ,,,10.10-20', ['192.168', '10.10-20']],
        ];
    }

    public function isInRangeData()
    {
        // ip, range, expected
        return [
            ['127.0.0.1', '127.0.0.1', true],
            [null, '127.0.0.1', false],
            ['127.0.0.1', null, false],
            [null, null, false],
            ['127.0.0.10', '127.0.0.1-100', true],
            ['10.0.0.160', '10.0.0.100-255', true],
            ['10.0.0.160', '10.0.0.100-160', true],
            ['10.0.0.160', '10.0.0.160-255', true],
            ['10.0.0.160', '10.0.0.100-159', false],
            ['10.0.0.160', '10.0.0.161-255', false],
            ['192.152.30.5', '192.152.1-64', true],
            ['192.152.30.5', '192.152.1-30', true],
            ['192.152.30.5', '192.152.1-29', false],
            ['192.152.30.5', '192.152', true],
            ['192.152.30.5', '192', true],
            ['192.152.30.5', '192-255', true],
            ['192.152.30.5', '128-191', false],
        ];
    }

    public function passData()
    {
        //user_ip, selection, expected
        return [
            ['', '', false],
            ['127.0.0.1', '127.0.0.1', true],
            ['127.0.0.1', '10.0.0.1, 127.0.0.1', true],
            ['127.0.0.1', '127.0.0.2-100', false],
            ['127.0.0.1', '127.0', true],
            ['127.0.0.1', '127', true],
            ['127.0.0.1', '1-127.0', true],
            ['192.168.10.52', '186-192.168.10', true],
            ['192.168.10.52', '186-192.168.11', false],
            ['192.168.10.52', '186-192.160-168.1-20', true],
            ['192.168.10.52', '186-192.160-168.1-20.52', true],
            ['192.168.10.52', '186-192.160-168.1-20.50', false],
            
        ];
    }

    /**
     * @dataProvider prepareRangesData
     */
    public function testPrepareRanges($ip_list, $expected)
    {
        $result = $this->invokeMethod($this->ip_assignment, 'prepareRanges', [$ip_list]);
        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider isInRangeData
     */
    public function testIsInRange($ip, $range, $expected)
    {
        $result = $this->invokeMethod($this->ip_assignment, 'isInRange', [$ip, $range]);
        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider passData
     */
    public function testPass($user_ip, $selection, $expected)
    {
        $this->options->selection = $selection;
        $this->ip_assignment = $this->getMockBuilder('\\NRFramework\\Assignments\\IP')
            ->setMethods(['value'])
            ->setConstructorArgs([$this->options, $this->factoryStub])
            ->getMock();

        $this->ip_assignment->method('value')->willReturn($user_ip);

        $this->assertEquals($expected, $this->ip_assignment->pass());
    }
}
