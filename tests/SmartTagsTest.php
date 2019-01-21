<?php

include_once __DIR__ . '/cases/TestCase.php';

class SmartTagsTest extends TestCase
{
    private $smart_tags;

    public function setUp()
    {
        parent::setUp();

        $smart_tags_mock = $this->getMockBuilder('\\NRFramework\\SmartTags')
            ->setConstructorArgs([[], $this->factoryStub])
            ->setMethods([
                'addSiteTags',
                'addPageTags',
                'addDateTags',
                'addOtherTags'
            ])
            ->getMock();
        
        $this->smart_tags = $smart_tags_mock;
    }

    public function testAdd()
    {
        $temp_tags = [
            'var1' => 'value_1',
            'var2' => 'value_2',
        ];

        $this->smart_tags->add($temp_tags, 'test.');

        $added_tags = $this->smart_tags->get();

        $result = is_array($added_tags) && array_key_exists('{test.var1}', $added_tags);

        $this->assertTrue($result);
    }

    public function replaceData()
    {
        $tes = [
            'contacts' => [
                'family' => [
                    'firstname' => '{user.firstname}',
                    'lastname'  => '{user.lastname}',
                    'more' => [
                        'firstname' => '{user.name}'
                    ]
                ]
            ]
        ];

        $tes1 = [
            'contacts' => [
                'family' => [
                    'firstname' => 'John',
                    'lastname'  => 'Doe',
                    'more' => [
                        'firstname' => 'John Doe'
                    ]
                ]
            ]
        ];

        // Array of Objects
        $obj1 = (object) [
            'firstname' => '{user.firstName}',
            'lastname'  => '{user.laStname}',
        ];
        $obj2 = (object) [
            'firstname' => 'John',
            'lastname'  => 'Doe',
        ];

        return [
            // Null or invalid cases
            [[], []],
            ['', ''],
            [' ', ' '],
            [null, null],
            [true, true],
            [150, 150],
            [15.5, 15.5],
            ['x', 'x'],

            // Case insensitive string replacements
            ['{user.firstname}', 'John'], 
            ['{user.fiRsTnAme} ', 'John '],
            ['{user.FIRSTNAME}', 'John'],
            ['Hello {user.firstname} {user.firstname}', 'Hello John John'],

            // Make sure unreplaced smart tags are stripped out
            ['Geia sou {user.test}', 'Geia sou '],
            ['Hello {querystring.name}', 'Hello '],

            // Arrays
            [['{user.firstname}', '{user.lastname}'], ['John', 'Doe']],
            [$tes, $tes1],

            // Objects
            [(object) ['{user.firstname}', '{user.lastname}'], (object) ['John', 'Doe']],
            [[$obj1, $obj1, $obj1], [$obj2, $obj2, $obj2]],
            [[[$obj1, $obj1, $obj1]], [[$obj2, $obj2, $obj2]]],
        ];
    }

    /**
     * @dataProvider replaceData
     */
    public function testReplace($subject, $expected)
    {
        $result = $this->smart_tags->replace($subject);

        $this->assertEquals($expected, $result);
    }
}