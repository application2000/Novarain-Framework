<?php

include_once __DIR__ . '/../cases/AssignmentTestCase.php';

use \NRFramework\Assignments\OS;
use \NRFramework\Factory;

class OSTest extends AssignmentTestCase
{
    public function passData()
    {
        return [
            //Android
            ['Mozilla/5.0 (Android; Mobile; rv:38.0) Gecko/38.0 Firefox/38.0', ['android'], true],
            ['Mozilla/5.0 (Linux; Android 5.0; SAMSUNG SM-G900F Build/LRX21T) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/2.1 Chrome/34.0.1847.76 Mobile Safari/537.36', ['android'], true],
            ['Mozilla/5.0 (Linux; Android 4.4.2; en-us; SAMSUNG SM-N900T Build/KOT49H) AppleWebKit/537.36 (KHTML, like Gecko) Version/1.5 Chrome/28.0.1500.94 Mobile Safari/537.36', ['android'], true],
            ['Mozilla/5.0 (Linux; U; Android 2.3.6; en-us; LGL35G/V100) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1', ['android'], true],

            //Linux
            ['Mozilla/5.0 (X11; Linux x86_64; rv:62.0) Gecko/20100101 Firefox/62.0', ['linux'], true],
            ['Mozilla/5.0 (X11; Linux x86_64; rv:45.0) Gecko/20100101 Thunderbird/45.3.0', ['linux'], true],
            ['Mozilla/5.0 (SMART-TV; X11; Linux armv7l) AppleWebKit/537.42 (KHTML, like Gecko) Chromium/25.0.1349.2 Chrome/25.0.1349.2 Safari/537.42', ['linux'], true],
            ['Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36', ['linux'], true],
            
            //Windows
            ['Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36', ['windows'], true],
            ['Mozilla/5.0 (Windows NT 5.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', ['windows'], true],
            ['Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.79 Safari/537.36 Edge/14.14393', ['windows'], true],
            ['Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko', ['windows'], true],

            //Mac
            ['Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/601.7.7 (KHTML, like Gecko) Version/9.1.2 Safari/601.7.7', ['mac'], true],
            ['Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_4) AppleWebKit/601.5.17 (KHTML, like Gecko)', ['mac'], true],
            ['Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36', ['mac'], true],
            ['Opera/9.80 (Macintosh; Intel Mac OS X 10.6.8; U; fr) Presto/2.9.168 Version/11.52', ['mac'], true],
            
            //IOS
            ['Mozilla/5.0 (iPad; CPU OS 9_3_2 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13F69 Safari/601.1', ['ios'], true],
            ['Mozilla/5.0 (iPad; CPU OS 6_1_3 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10B329 Safari/8536.25', ['ios'], true],
            ['Mozilla/5.0 (iPad; CPU OS 10_2 like Mac OS X) AppleWebKit/602.1.50 (KHTML, like Gecko) CriOS/55.0.2883.79 Mobile/14C92 Safari/602.1', ['ios'], true],
            ['Mozilla/5.0 (iPhone; CPU iPhone OS 9_2_1 like Mac OS X) AppleWebKit/600.1.4 (KHTML, like Gecko) GSA/13.1.72140 Mobile/13D15 Safari/600.1.4', ['ios'], true],

            //Blackberry
            ['Opera/9.80 (BlackBerry; Opera Mini/7.1.33553/31.1325; U; en) Presto/2.8.119 Version/11.10', ['blackberry'], true],
            ['Mozilla/5.0 (BlackBerry; U; BlackBerry 9780; id) AppleWebKit/534.1+ (KHTML, like Gecko) Version/6.0.0.285 Mobile Safari/534.1+', ['blackberry'], true],
            ['Dalvik/1.6.0 (Linux; U; Android 4.4.2; Blackberry Build/KOT49H) NOKIA', ['blackberry'], true],
            ['UCWEB/2.0(BlackBerry; U; 5.1.0.429; en-us; 9930/5.1.0.429) U2/1.0.0 UCBrowser/8.1.0.216', ['blackberry'], true],
            
            //ChromeOS
            ['Mozilla/5.0 (X11; CrOS x86_64 10176.72.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.167 Safari/537.36', ['chromeos'], true],
            ['Mozilla/5.0 (X11; CrOS x86_64 8743.83.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.93 Safari/537.36', ['chromeos'], true],
            ['Mozilla/5.0 (X11; CrOS armv7l 7077.95.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.90 Safari/537.36', ['chromeos'], true],

            //
            ['Mozilla/5.0 (Windows NT 5.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', ['windows', 'linux'], true],
            ['Mozilla/5.0 (Windows NT 5.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', ['mac', 'linux'], false],
            ['Mozilla/5.0 (Windows NT 5.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', [''], false],
            ['Mozilla/5.0 (Windows NT 5.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', [null], false],
        ];
    }

    /**
     * @dataProvider passData
     */
    public function testPass($ua, $selection, $expected)
    {
        $this->options->selection = $selection;

        $this->os_assignment = $this->getMockBuilder('\\NRFramework\\Assignments\\OS')
            ->setMethods(['value'])
            ->setConstructorArgs([$this->options, $this->factoryStub])            
            ->getMock();

        $os = \NRFramework\WebClient::getOS($ua);
        $this->os_assignment->method('value')->willReturn($os);

        $result = $this->os_assignment->pass();
        $this->assertEquals($expected, $result);
    }
}