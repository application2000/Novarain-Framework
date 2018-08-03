<?php

include_once __DIR__ . '/../cases/WrapperTestCase.php';

class ElasticEmailTest extends WrapperTestCase
{
    static $credentials = ['api' => '7d00b337-e670-40c7-b0f3-f4ec14a341a6'];
    static $invalid_path_error = 'Invalid API path';

    public function testPublicAccount()
    {
        $this->assertTrue(!empty(self::$wrapper->getPublicAccountID()));
    }

    public function testAddList()
    {
        $wrapper = self::$wrapper;
        $wrapper->get('/list/add', [
            'apikey'   => self::$credentials['api'],
            'listName' => 'UnitTest',
            'emails'   => 'test@mail.com'
        ]);

        $this->assertTrue($wrapper->success());
    }

    public function subscribeProvider()
    {
        return [
            ['john@mail.', [], true, false, 'address is invalid'],
            ['john@mailinator.com', [], true, false, 'domain is not allowed'],
            ['john@mail.com']
        ];
    }

    /**
     * @dataProvider subscribeProvider
     */
	public function testSubscribe($email, $params = array(), $update_existing = true, $double_optin = false, $expected = true)
    {
        $wrapper = self::$wrapper;
        $wrapper->subscribe($email, $this->getFirstListID(), $wrapper->getPublicAccountID(), $params, $update_existing, $double_optin);

        $this->assertSubscribe($expected);
    }

    public static function tearDownAfterClass()
    {
        $wrapper = self::$wrapper;
        $lists = $wrapper->getLists();

        foreach ($lists as $key => $list)
        {
            $wrapper->get('/list/delete', [
                'apikey'   => self::$credentials['api'],
                'listName' => $list['name']
            ]);
        }
    }
}