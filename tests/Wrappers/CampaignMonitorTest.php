<?php

include_once __DIR__ . '/../cases/WrapperTestCase.php';

class CampaignMonitorTest extends WrapperTestCase
{
    static $credentials = ['api' => '9445bf0943bc3713f408d1d5701165581deb94f94f4eb4c5'];
    public $listid = '87ade79b3bb302d262c0c0f98c0fc03a';
    static $invalid_path_error = 'We couldn\'t find the resource';

    public function subscribeProvider()
    {
        return [
            ['john@mail.', '', [], 'Invalid Email Address'],
            ['john@mail.com', 'John'],
            ['peter66@mail.com', 'Peter', ['Address' => 'Kos', 'Favorite Fruites' => ['Bannana', 'Orange', 'Apple']]],
            ['peter67@mail.com', 'Peter', ['Favorite Fruites' => 'Bannana']],
            ['maria@mail.com', 'Maria', ['Favorite Fruites' => null]]
        ];
    }

    /**
     * @dataProvider subscribeProvider
     */
    public function testSubscribe($email, $name = '', $customFields = [], $expected = true)
    {
        $wrapper = self::$wrapper;
        $wrapper->subscribe($email, $name, $this->listid, $customFields);

        $this->assertSubscribe($expected);
    }
}