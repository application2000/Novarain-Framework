<?php

include_once __DIR__ . '/../cases/WrapperTestCase.php';

/**
 * This UnitTest is likely to fail as it's based on a 30day Trial Account. 
 */
class ActiveCampaignTest extends WrapperTestCase
{
    static $credentials = [
        'api'      => '6c75049ae2e17893601cb070df739ad2640198e892b598185abc42121a2f0d147612c178',
        'endpoint' => 'https://mailinator87069.api-us1.com'
    ];

    public function testAddList()
    {
        $wrapper = self::$wrapper;
        $wrapper->post('', [
            'api_action'      => 'list_add',
            'name'            => 'UnitTest',
            'sender_addr1'    => 'no@mail.com',
            'sender_name'     => 'Company',
            'sender_addr1'    => 'Street ',
            'sender_zip'      => '12345', 
            'sender_city'     => 'New York',
            'sender_country'  => 'US',
            'sender_url'      => 'http://www.google.gr', 
            'sender_reminder' => 'You subscribed on our web site',
        ]);

        $this->assertTrue($wrapper->success());
    }

    public function subscribeProvider()
    {
        return [
            ['john@mail', 'John', '', [], true, 'Contact Email Address is not valid'],
            ['john@mail.com', 'John'],
            ['john@mail2.com', 'John'],
            ['john@mail2.com', 'John', '', [], false, 'does not allow duplicates']
        ];
    }

    /**
     * @dataProvider subscribeProvider
     */
	public function testSubscribe($email, $name, $tags = '', $customfields = [], $updateexisting = true, $expected = true)
    {
        $wrapper = self::$wrapper;
        $wrapper->subscribe($email, $name, $this->getFirstListID(), $tags, $customfields, $updateexisting);

        $this->assertSubscribe($expected);
    }

    public static function tearDownAfterClass()
    {
        $wrapper = self::$wrapper;

        $lists = $wrapper->getLists();
        $ids = array_column($lists, 'id');
        $ids = implode(',', $ids);

        $wrapper->post('', [
            'api_action' => 'list_delete_list',
            'ids'        => $ids
        ]);
    }
}