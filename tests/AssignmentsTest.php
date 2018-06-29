<?php

class AssignmentsTest extends PHPUnit\Framework\TestCase
{
    protected $assignments;

    public function setUp()
    {
        $this->assignments = new NRFramework\Assignments();
    }

    /**
     * data provider for testAliasToClassname
     */
    public function aliasToClassnameProvider()
    {
        return [
            ['acymailing', 'AcyMailing'],
            ['akeebasubs', 'AkeebaSubs'],
            ['contentcats','ContentCategory'],
            ['article', 'ContentArticle'],
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
    // public function testPassStateCheck()
    // {
    //     $this->assertTrue(true);
    // }
    // public function testPrepareAssignmentsInfo()
    // {
    //     $this->assertTrue(true);
    // }

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

    // match method 
}