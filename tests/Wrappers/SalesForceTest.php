<?php

include_once __DIR__ . '/../cases/WrapperTestCase.php';

class SalesForceTest extends WrapperTestCase
{
    static $credentials = ['api' => '00D1r000002dOQd'];
    static $invalid_path_error = 'PLG_CONVERTFORMS_SALESFORCE_ERROR';

    public function subscribeProvider()
    {
        return [
            ['email1@mail'],
            ['email2@mail.com', ['first_name' => 'John', 'last_name' => 'Johnson']],
        ];
    }

    /**
     * @dataProvider subscribeProvider
     */
    public function testSubscribe($email, $params = array(), $expected = true)
    {        
        $wrapper = self::$wrapper;
        $wrapper->subscribe($email, $params);

        $this->assertSubscribe($expected);
    }
}