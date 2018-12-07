<?php

include_once __DIR__ . '/TestCase.php';

class AssignmentTestCase extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->options = (object) [
            'params' => null,
            'selection' => null,
            'assignment_state' => null
        ];
    }
}