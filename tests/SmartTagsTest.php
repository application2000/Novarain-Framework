<?php

include_once __DIR__ . '/cases/TestCase.php';

class SmartTagsTest extends TestCase
{
    private $smart_tags;

    private $tags_collection = [
        'firstname' => 'John',
        'lastname'  => 'Doe',
        'age'       => '26',
        'country'   => 'United States',
        'city'      => 'New York'
    ];

    public function setUp()
    {
        parent::setUp();

        $smart_tags_mock = $this->getMockBuilder('\\NRFramework\\SmartTags')
            ->setConstructorArgs([[], $this->factoryStub])
            ->setMethods(['addDefaultTags'])
            ->getMock();
        
        $this->smart_tags = $smart_tags_mock;
    }

    public function testAdd()
    {
        $this->smart_tags->add($this->tags_collection, 'test.');

        $added_tags = $this->smart_tags->get();

        $result = is_array($added_tags) && count($added_tags) == 5 && array_key_exists('{test.firstname}', $added_tags);

        $this->assertTrue($result);
    }

    public function replaceData()
    {
        $tes = [
            'contacts' => [
                'family' => [
                    'firstname' => '{firstname}',
                    'lastname'  => '{lastname}',
                    'age'  => '{age}',
                    'test'  => '{test}',
                    'test1' => [
                        'geia' => '{firstname}'
                    ]
                ]
            ]
        ];

        $tes1 = [
            'contacts' => [
                'family' => [
                    'firstname' => 'John',
                    'lastname'  => 'Doe',
                    'age'  => '26',
                    'test'  => '{test}',
                    'test1' => [
                        'geia' => 'John'
                    ]
                ]
            ]
        ];

        // Array of Objects
        $obj1 = (object) [
            'firstname' => '{firstName}',
            'lastname'  => '{laStname}',
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
            ['{firstname}', 'John'], 
            ['{fiRsTnAme} ', 'John '],
            ['{FIRSTNAME}', 'John'],
            ['Hello {firstname} {firstname}', 'Hello John John'],
            ['{AGE}', '26'],

            // Make sure unreplaced smart tags are stripped out
            ['Geia sou {user.test}', 'Geia sou '],
            ['Hello {querystring.name}', 'Hello '],

            // Arrays
            [['{firstname}', '{lastname}'], ['John', 'Doe']],
            [$tes, $tes1],

            // Objects
            [(object) ['{firstname}', '{lastname}'], (object) ['John', 'Doe']],
            [[$obj1, $obj1, $obj1], [$obj2, $obj2, $obj2]],
            [[[$obj1, $obj1, $obj1]], [[$obj2, $obj2, $obj2]]],
        ];
    }

    /**
     * @dataProvider replaceData
     */
    public function testReplace($subject, $expected)
    {
        $this->smart_tags->add($this->tags_collection);
        $this->smart_tags->add(['id' => '1'], 'querystring.');
        $this->smart_tags->add(['id' => '1'], 'user.');

        $result = $this->smart_tags->replace($subject);

        $this->assertEquals($expected, $result);
    }
}