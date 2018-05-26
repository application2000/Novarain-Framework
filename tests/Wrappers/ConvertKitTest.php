<?php

include_once __DIR__ . '/../cases/WrapperTestCase.php';

class ConvertKitTest extends WrapperTestCase
{
    static $credentials = ['api' => 'DomAOFW2Ps-r2DOxG4lrUA'];
    static $invalid_path_error = 'Not Found';
    private $form = '401395';

    public function subscribeProvider()
    {
        return [
            ['john@mail', ['first_name' => 'John'], 'Email address is invalid'],
            ['john@mail.com', ['first_name' => 'John']]
        ];
    }

    /**
     * @dataProvider subscribeProvider
     */
	public function testSubscribe($email, $params, $expected = true)
    {
        $wrapper = self::$wrapper;
        $wrapper->subscribe($email, $this->form, $params);

        $this->assertSubscribe($expected);
    }
}