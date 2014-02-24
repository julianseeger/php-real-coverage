<?php

namespace PHPRealCoverage;

use PHPUnit_Framework_TestCase;

class ExampleTest extends PHPUnit_Framework_TestCase
{
    public function testThatBuildScriptsWork()
    {
        $sut = new Example();
        $this->assertTrue($sut->returnsTrue());
    }
}
