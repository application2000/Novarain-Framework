<?php

include_once __DIR__ . '/../cases/WrapperTestCase.php';

/**
 * This UnitTest is likely to fail as it's based on a 30day Trial Account. 
 */
class GetResponseTest extends WrapperTestCase
{
    static $credentials = [
        'api'  => '0e45f3c99f56e2a4d0a90d1acd917c76'
    ];

    public function subscribeProvider()
    {
        return [
            ['john@mail', 'John', [], true, 'Incorrect email format'],
            ['john@mailinator.com', 'John', [], true, 'Cannot add contact that is blacklisted'],
        ];
    }

    /**
     * @dataProvider subscribeProvider
     */
	public function testSubscribe($email, $name = '', $customfields = [], $updateexisting = true, $expected = true)
    {
        $wrapper = self::$wrapper;
        $wrapper->subscribe($email, $name, '69htv', $customfields, $updateexisting);

        $this->assertSubscribe($expected);
    }
}