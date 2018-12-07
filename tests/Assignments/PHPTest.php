<?php

include_once __DIR__ . '/../cases/AssignmentTestCase.php';

use \NRFramework\Assignments\PHP;
use \NRFramework\Factory;
use \NRFramework\Executer;

class PHPTest extends AssignmentTestCase
{
    public function passDataProvider()
    {
        return [
            ['', true],
            [null, true],
            ['return true;', true],
            ['return false;', false],
            ['$x = true; return $x;', true],
            ['$arr = ["apple", "orange", "bannana"]; return in_array("apple", $arr);', true]
        ];
    }

    /**
     * @dataProvider passDataProvider
     */
    public function testPHPPass($php, $expected)
    {
        $executer = $this->getMockBuilder("\\NRFramework\\Executer")
            ->setConstructorArgs([$php])
            ->setMethods(['getFunctionVariables'])
            ->getMock();

        $executer->method('getFunctionVariables')->willReturn([]);

        $this->factoryStub  
             ->method('getExecuter')
             ->with($php)
             ->willReturn($executer);

        $this->options->selection = $php;

        $assignment = new PHP($this->options, $this->factoryStub);

        $this->assertEquals($expected, $assignment->pass());
    }
}