<?php

use \NRFramework\Assignments;

class AssignmentsTest extends PHPUnit\Framework\TestCase
{
    protected $assignments;
    protected $factory;

    public function setUp()
    {
        $this->factory = $this->createMock('\\NRFramework\\Factory');

        // $user = new JUser;
        // $user->id       = 15;
        // $user->name     = 'John Doe';
        // $user->username = 'johndoe';

        // $this->factory->method('getUser')->willReturn($user);

        $this->assignments = new Assignments($this->factory);
    }

    /**
     * data provider for testprepareAssignmentsFromObject
     *
     * @return void
     */
    public function prepareAssignmentsFromObject()
    {
        $data1 = [
            'dummyproperty1' => true,
            'dummy_property2' => 0,
            'assign_urls' => '1',
            'assign_urls_list' => ['/blog/test', '/blog/test2'],
            'assign_urls_param_regex' => '0',
            'assign_devices' => '1',
            'assign_devices_list' => ['desktop']
        ];

        $data2 = [
            'dummyproperty1' => true,
            'dummy_property2' => 0,
            'assign_urls' => '1',
            'assign_urls_list' => ['/blog/test', '/blog/test2'],
            'assign_urls_param_regex' => '0'
        ];

        return [
            [$data1, 2, 'or'],
            [$data1, 1, 'and'],
            [$data2, 1, 'and'],
            [$data2, 1, 'or']
        ];
    }

    /**
     * data provider for testAliasToClassname
     */
    public function aliasToClassnameProvider()
    {
        return [
            ['acymailing', 'AcyMailing'],
            ['akeebasubs', 'AkeebaSubs'],
            ['contentcats','Component\ContentCategory'],
            ['article', 'Component\ContentArticle'],
            ['components', 'Component'],
            ['convertforms', 'ConvertForms'],
            ['nonexistent-alias', null]
        ];
    }

    /**
     *  data for testExists
     */
    public function existsProvider()
    {
        return [
            ['device|devices', true],
            ['Devices', true],
            ['urls|url', true],
            ['URLs', true],
            ['os', true],
            ['OS', true],
            ['nonexistent_assignment', false]
        ];
    }

    public function passAllProvider()
    {
        // 1 assignment
        $assignments0 = [
            [
                (object) ['alias' => 'url', 'value' => '?t=1', 'assignment_state' => '1'],
            ]
        ];

        // 2 assignments AND
        $assignments1 = [
            [
                (object) ['alias' => 'device', 'value' => ['desktop'], 'assignment_state' => '1'],
                (object) ['alias' => 'url', 'value' => '?t=1', 'assignment_state' => '1'],
                (object) ['alias' => 'browser', 'value' => ['firefox'], 'assignment_state' => '1']
            ]
        ];

        // 2 assignments OR
        $assignments2 = [
            [
                (object) ['alias' => 'device', 'value' => ['desktop'], 'assignment_state' => '1'],
            ],
            [
                (object) ['alias' => 'url', 'value' => '?t=1', 'assignment_state' => '0']
            ],
            [
                (object) ['alias' => 'browser', 'value' => ['firefox'], 'assignment_state' => '0']
            ]
        ];

        // 2 assignments AND
        $assignments3 = [
            [
                (object) ['alias' => 'device', 'value' => ['mobile'], 'assignment_state' => '1'],
                (object) ['alias' => 'url', 'value' => '?t=1', 'assignment_state' => '1'],
                (object) ['alias' => 'browser', 'value' => ['firefox'], 'assignment_state' => '1']
            ]
        ];

        // 2 assignments AND
        $assignments4 = [
            [
                (object) ['alias' => 'device', 'value' => ['mobile'], 'assignment_state' => '1'],
                (object) ['alias' => 'url', 'value' => '?t=2', 'assignment_state' => '1'],
                (object) ['alias' => 'browser', 'value' => ['firefox'], 'assignment_state' => '1']
            ]
        ];

        // 2 assignments AND
        $assignments5 = [
            [
                (object) ['alias' => 'device', 'value' => ['mobile'], 'assignment_state' => '1'],
                (object) ['alias' => 'url', 'value' => '?t=2', 'assignment_state' => '1'],
                (object) ['alias' => 'browser', 'value' => ['chrome'], 'assignment_state' => '1']
            ]
        ];

        // 2 assignments AND
        $assignments6 = [
            [
                (object) ['alias' => 'device', 'value' => ['mobile'], 'assignment_state' => 'exclude'],
                (object) ['alias' => 'url', 'value' => '?t=2', 'assignment_state' => 'exclude'],
                (object) ['alias' => 'browser', 'value' => ['firefox'], 'assignment_state' => '1']
            ]
        ];

        return [
            [$assignments0, true],
            [$assignments1, true],
            [$assignments2, true],
            [$assignments3, false],
            [$assignments4, false],
            [$assignments5, false],
            [$assignments6, true],
            [[[]], true],              // Succeed if we pass no assignments
            [[], true]                 // Succeed if we pass no assignments
        ];
    }

    /**
     * @dataProvider passAllProvider
     */
    public function testPassAll($assignments, $expected)
    {
        $this->factory->method('getURL')->willReturn('http://www.google.gr?t=1');
        $this->factory->method('getDevice')->willReturn('desktop');
        $this->factory->method('getBrowser')->willReturn(['name' => 'firefox']);

        $pass = $this->assignments->passAll($assignments);

        $this->assertEquals($expected, $pass);
    }

    /**
     * @dataProvider existsProvider
     */
    public function testExists($name, $expected)
    {
        $this->assertEquals($expected, $this->assignments->exists($name));
    }

    /**
     * @dataProvider aliasToClassnameProvider
     */
    public function testAliasToClassname($alias, $expected)
    {
        $this->assertEquals($expected, $this->assignments->aliasToClassname($alias));
    }

    /**
     * @dataProvider prepareAssignmentsFromObject
     */
    public function testPrepareAssignmentsFromObject($assignments, $count_groups, $mathing_method)
    {
        $data = (object) [
            'id'     => 0,
            'name'   => 'test',
            'params' => json_encode($assignments)
        ];

        $result = $this->assignments->prepareAssignmentsFromObject($data, $mathing_method);

        $pass = is_array($result) && count($result) == $count_groups;
        $this->assertTrue($pass);
    }
}