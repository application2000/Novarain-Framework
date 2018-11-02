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
            [$data1, 1, 2, 'or', true],
            [$data1, 2, 1, 'and', true],
            [$data2, 1, 1, 'and', true],
            [$data2, 1, 1, 'or', true]
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
        // 1 assignment (include)
        $assignments0 = [
            [
                (object) ['alias' => 'url', 'value' => '?t=1', 'assignment_state' => '1'],
            ]
        ];

        // 2 assignments (include)
        $assignments1 = [
            [
                (object) ['alias' => 'device', 'value' => ['desktop'], 'assignment_state' => '1'],
            ],
            [
                (object) ['alias' => 'url', 'value' => '?t=1', 'assignment_state' => '1']
            ]
        ];

        // 1 assignment (exclude)
        $assignments2 = [
            [
                (object) ['alias' => 'url', 'value' => '?t=1', 'assignment_state' => '0'],
            ]
        ];

        // 2 assignments (exclude)
        $assignments3 = [
            [
                (object) ['alias' => 'device', 'value' => ['desktop'], 'assignment_state' => '0'],
            ],
            [
                (object) ['alias' => 'url', 'value' => '?t=1', 'assignment_state' => '0']
            ]
        ];

        $env1 = [
            'url'  => 'http://www.google.gr'
        ];

        $env2 = [
            'url'  => 'http://www.google.gr?amp=true&t=1'
        ];

        return [
            [
                $env1, $assignments1, false,
                $env2, $assignments1, true,
                $env1, $assignments0, false,
                $env2, $assignments0, true,
                $env1, $assignments2, true,
                $env2, $assignments2, false,
                $env1, [], true                 // Succeed if we pass no assignments
            ]
        ];
    }

    /**
     * @dataProvider passAllProvider
     */
    public function testPassAll($environment, $assignments, $expected)
    {
        $this->factory->method('getURL')->willReturn($environment['url']);

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
    public function testPrepareAssignmentsFromObject($assignments, $count_and, $count_or, $mathing_method, $expected)
    {
        $data = (object) [
            'id'     => 0,
            'name'   => 'test',
            'params' => json_encode($assignments)
        ];

        $result = $this->assignments->prepareAssignmentsFromObject($data, $mathing_method);

        $pass = is_array($result) && count($result) == $count_and && count($result[0]) == $count_or;
        $this->assertEquals($pass, $expected);
    }
}