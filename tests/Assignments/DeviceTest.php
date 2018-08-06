<?php

include_once __DIR__ . '/../cases/AssignmentTestCase.php';

use \NRFramework\Assignments\Device;
use \NRFramework\Factory;

class DeviceTest extends AssignmentTestCase
{
    public function passData()
    {
        return [
            //desktop
            ['Mozilla/5.0 (X11; Linux x86_64; rv:62.0) Gecko/20100101 Firefox/62.0', ['desktop'], true],
            ['Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36', ['desktop'], true],
            ['Mozilla/5.0 (X11; Linux x86_64; rv:45.0) Gecko/20100101 Thunderbird/45.3.0', ['desktop'], true],
            ['Mozilla/4.0 (compatible; MSIE 9.0; Windows NT 6.1)', ['desktop'], true],
            ['Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.79 Safari/537.36 Edge/14.14393', ['desktop'], true],
            ['Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko', ['desktop'], true],
            ['Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/601.7.8 (KHTML, like Gecko) Version/9.1.3 Safari/601.7.8', ['desktop'], true],

            //mobile
            ['Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_1 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/10.0 Mobile/14E304 Safari/602.1', ['mobile'], true],
            ['Mozilla/5.0 (Linux; Android 7.1; Mi A1 Build/N2G47H) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.83 Mobile Safari/537.36', ['mobile'], true],
            ['Dalvik/1.6.0 (Linux; U; Android 4.0.4; opensign_x86 Build/IMM76L)', ['mobile'], true],
            ['Mozilla/5.0 (Mobile; LYF/F90M/LYF-F90M-000-02-28-130318; Android; rv:48.0) Gecko/48.0 Firefox/48.0 KAIOS/2.0', ['mobile'], true],
            ['Mozilla/5.0 (Linux; U; Android 5.0.2; zh-CN; Redmi Note 3 Build/LRX22G) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 OPR/11.2.3.102637 Mobile Safari/537.36', ['mobile'], true],
            ['Mozilla/5.0 (Linux; Android 4.1.2; Nokia_X Build/JZO54K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.82 Mobile Safari/537.36 NokiaBrowser/1.2.0.12', ['mobile'], true],

            //tablet
            ['Mozilla/5.0 (iPad; CPU OS 9_3_2 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13F69 Safari/601.1', ['tablet'], true],
            ['Mozilla/5.0 (Linux; Android 6.0.1; SM-T800 Build/MMB29K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.107 Safari/537.36', ['tablet'], true],
            ['Mozilla/5.0 (Linux; U; Android 4.0.4; en-gb; GT-P5100 Build/IMM76D) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Safari/534.30', ['tablet'], true],
            ['Mozilla/5.0 (iPad; CPU OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25', ['tablet'], true],
            ['Mozilla/5.0 (Linux; Android 7.0; Nexus 9 Build/NRD90R) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.124 Safari/537.36', ['tablet'], true],

            //
            ['Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_1 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/10.0 Mobile/14E304 Safari/602.1', ['desktop', 'mobile'], true],
            ['Mozilla/5.0 (Linux; Android 7.0; Nexus 9 Build/NRD90R) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.124 Safari/537.36', ['desktop', 'mobile'], false],
            ['', [''], false],
            ['', [null], false],
        ];
    }

    /**
     * @dataProvider passData
     */
    public function testPass($ua, $selection, $expected)
    {
        $this->options->selection = $selection;

        $this->device_assignment = $this->getMockBuilder('\\NRFramework\\Assignments\\Device')
            ->setConstructorArgs([$this->options, $this->factoryStub])
            ->setMethods(['value'])
            ->getMock();

        $this->device_assignment->method('value')->willReturn(\NRFramework\WebClient::getDeviceType($ua));

        $result = $this->device_assignment->pass();
        $this->assertEquals($expected, $result);
    }
}