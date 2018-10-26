<?php

include_once __DIR__ . '/../cases/WrapperTestCase.php';

/**
 * This UnitTest is likely to fail as it's based on a 30day Trial Account. 
 */
class ActiveCampaignTest extends WrapperTestCase
{
    static $credentials = [
        'api'      => '59d5b85a362f6880e0bfb441eab324c9cc423adb695c8400c9211e7e46d791307db55c9e',
        'endpoint' => 'https://mailinator9617.api-us1.com'
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
            ['john@mail', '', '', [], true, 'Contact Email Address is not valid'],
            ['lebronxx@mail.com', 'Lebron James'],
            ['johndoe@mail.com', '', '', ['first_name' => 'John', 'Last Name' => 'Doe']],
            ['helen@mail.com', '', '', ['first_name' => 'Helen']],
            ['marydoe@mail.com', 'Mary Doe'],
            ['marydoe@mail.com', 'Mary Doe', '', [], false, 'does not allow duplicates']
        ];
    }

    /**
     * @dataProvider subscribeProvider
     */
	public function testSubscribe($email, $name = null, $tags = '', $customfields = [], $updateexisting = true, $expected = true)
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