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
            ['contentcats','Content.Categories'],
            ['article', 'Content.Articles'],
            ['components', 'Components'],
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
            // 'browsers|browser'		             => 'Browsers',
            // 'referrer'                           => 'URLs.Referrer',
            // 'lang|language|languages'            => 'Languages',
            // 'php'                                => 'PHP',
            // 'timeonsite'                         => 'Users.TimeOnSite',
            // 'usergroups|usergroup|user_groups'   => 'Users.GroupLevels',
            // 'pageviews|user_pageviews'           => 'Users.Pageviews',
            // 'user_id|userid'		             => 'Users.IDs',
            // 'menu'                               => 'Menu',
            // 'datetime|daterange|date'            => 'DateTime.Date',
            // 'days|day'                           => 'DateTime.Days',
            // 'months|month'                       => 'DateTime.Months',
            // 'timerange|time'                     => 'DateTime.TimeRange',
            // 'acymailing'                         => 'AcyMailing',
            // 'akeebasubs'                         => 'AkeebaSubs',
            // 'contentcats|categories|category'    => 'Content.Categories',
            // 'contentarticles|articles|article'   => 'Content.Articles',
            // 'components|component'	             => 'Components',
            // 'convertforms'	                     => 'ConvertForms',
            // 'geo_country|country|countries'	     => 'GeoIP.Countries',
            // 'geo_continent|continent|continents' => 'GeoIP.Continents',
            // 'cookiename|cookie'                  => 'Cookies.Name',
            // 'ip_addresses|iprange|ip'            => 'IP.Range',
            // 'k2_items'                           => 'K2Item',
            // 'k2_cats'                            => 'K2Category',
            // 'k2_tags'                            => 'K2Tag',
            // 'k2_pagetypes'                       => 'K2Pagetype'
        ];
    }
    public function testPassStateCheck()
    {
        $this->assertTrue(true);
    }
    public function testPrepareAssignmentsInfo()
    {
        $this->assertTrue(true);
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
}