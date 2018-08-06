<?php

include_once __DIR__ . '/../cases/AssignmentTestCase.php';

use \NRFramework\Assignments\Browser;
use \NRFramework\Factory;

class BrowserTest extends AssignmentTestCase
{
    public function setUp()
    {
        $this->options = (object) [
            'params' => null,
            'selection' => null,
            'assignment_state' => null
        ];

        $this->factoryStub = $this->getMockBuilder('\\NRFramework\\Factory')
            ->setMethods(['getDbo', 'getUser', 'getApplication', 'getDocument'])
            ->getMock();

        
    }

    public function passData()
    {
        return [
            // Chrome
            ['Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.157 Safari/537.36', ['chrome'], true],
            ['Mozilla/5.0 (Linux; Android 6.0; vivo 1713 Build/MRA58K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.124 Mobile Safari/537.36', ['chrome'], true],
            ['Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36', ['chrome'], true],
            ['Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36', ['chrome'], true],

            //Firefox
            ['Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', ['firefox'], true],
            ['Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:57.0) Gecko/20100101 Firefox/57.0', ['firefox'], true],
            ['Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:40.0) Gecko/20100101 Firefox/40.0', ['firefox'], true],
            ['Mozilla/5.0 (Android; Mobile; rv:40.0) Gecko/40.0 Firefox/40.0', ['firefox'], true],

            //Edge
            ['Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.79 Safari/537.36 Edge/14.14393', ['edge'], true],
            ['Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2486.0 Safari/537.36 Edge/13.10586', ['edge'], true],
            ['Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2486.0 Safari/537.36 Edge/13.10586', ['edge'], true],
            ['Mozilla/5.0 (Windows Phone 10.0; Android 6.0.1; Xbox; Xbox One) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Mobile Safari/537.36 Edge/16.16295', ['edge'], true],

            //IE
            ['Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)', ['ie'], true],
            ['Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2)', ['ie'], true],
            ['Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; SMJB; rv:11.0) like Gecko', ['ie'], true],
            ['Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.0; Trident/5.0; BOIE9;FRBE)', ['ie'], true],


            //Safari
            ['Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.21 (KHTML, like Gecko) Mwendo/1.1.5 Safari/537.21', ['safari'], true],
            ['Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/601.7.7 (KHTML, like Gecko) Version/9.1.2 Safari/601.7.7', ['safari'], true],
            ['Mozilla/5.0 (iPad; CPU OS 9_3_2 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13F69 Safari/601.1', ['safari'], true],
            ['Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_5) AppleWebKit/600.8.9 (KHTML, like Gecko) Version/6.2.8 Safari/537.85.17', ['safari'], true],

            //Opera
            ['Mozilla/5.0 (Windows NT 5.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36 OPR/42.0.2393.94', ['opera'], true],
            ['Opera/9.80 (X11; Linux zvav; U; en) Presto/2.12.423 Version/12.16', ['opera'], true],
            ['Mozilla/5.0 (Linux; U; Android 5.0.2; zh-CN; Redmi Note 3 Build/LRX22G) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 OPR/11.2.3.102637 Mobile Safari/537.36', ['opera'], true],
            ['Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.82 Safari/537.36 OPR/39.0.2256.43', ['opera'], true],

            //
            ['Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)', ['firefox'], false],
            ['Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)', ['firefox', 'chrome'], false],
            ['Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)', ['ie', 'firefox'], true],
            ['Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)', [''], false],
            ['Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)', [null], false],
            ['Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)', null, false],
        ];
    }

    /**
     * @dataProvider passData
     */
    public function testPass($ua, $selection, $expected)
    {
        $this->options->selection = $selection;

        $this->browser_assignment = $this->getMockBuilder('\\NRFramework\\Assignments\\Browser')
            ->setConstructorArgs([$this->options, $this->factoryStub])
            ->setMethods(['value'])
            ->getMock();

        $this->browser_assignment->method('value')->willReturn(\NRFramework\WebClient::getBrowser($ua)['name']);

        $this->assertEquals($expected, $this->browser_assignment->pass());
    }
}