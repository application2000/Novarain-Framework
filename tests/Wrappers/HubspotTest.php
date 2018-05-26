<?php

include_once __DIR__ . '/../cases/WrapperTestCase.php';

class HubspotTest extends WrapperTestCase
{
    static $credentials = ['api' => '1aac4b50-cc7d-471b-bfcb-542bbf9449af'];

    public function subscribeProvider()
    {
        return [
            ['john@mail.', [], 'Email address john@mail. is invalid'],
            ['john@mail.com', ['first_name' => 'John']],
        ];
    }

    /**
     * @dataProvider subscribeProvider
     */
	public function testSubscribe($email, $params, $expected = true)
    {
        $wrapper = self::$wrapper;
        $wrapper->subscribe($email, $params);

        $this->assertSubscribe($expected);
    }
}