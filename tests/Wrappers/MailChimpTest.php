<?php

include_once __DIR__ . '/../cases/WrapperTestCase.php';

class MailChimpTest extends WrapperTestCase
{
    static $credentials = [
        'api' => 'b2a476a53c84dd3d9fd886f542e81756-us5'
    ];

    static $invalid_path_error = 'could not find resource';

    /**
     * Add a temprorary list to work with
     */
    public function testAddList()
    {
        $wrapper = self::$wrapper;
        $wrapper->post('/lists', [
            'name' => 'UnitTest',
            'contact' => [
                'company' => 'UnitTest',
                'address1' => 'New York',
                'city' => 'New York',
                'state' => 'US',
                'zip' => '123456',
                'country' => 'US'
            ],
            'campaign_defaults' => [
                'from_name' => 'UnitTest',
                'from_email' => 'info@unittest.kos',
                'subject' => 'Subject',
                'language' => 'en'
            ],
            'permission_reminder' => 'Permission Reminder Text',
            'email_type_option' => true
        ]);

        $this->assertTrue($wrapper->success());
    }
    
    public function subscribeProvider()
    {
        return [
            ['', [], 'This value should not be blank'],
            ['hello@mailinator.com', [], 'looks fake or invalid'],
            ['mdoclli9@yahoo.com', [], true],
            ['poelmdnue@hotmail.com', ['FNAME' => 'Tassos'], true],
        ];
    }

    /**
     * Test subscribe() method
     * 
     * @dataProvider subscribeProvider
     */
    public function testSubscribe($email, $merge_fields, $expected = true)
    {
        $wrapper = self::$wrapper;
        $wrapper->subscribe($email, $this->getFirstListID(), $merge_fields, true, false);

        $this->assertSubscribe($expected);
    }

    /**
     * Cleanup service when all tests has finished
     */
    public static function tearDow1nAfterClass()
    {
        $wrapper = self::$wrapper;

        $lists = $wrapper->getLists();

        foreach ($lists as $key => $list)
        {
            $wrapper->delete('/lists/' . $list['id']);
        }
    }
}