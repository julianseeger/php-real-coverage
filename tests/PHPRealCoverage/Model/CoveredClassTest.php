<?php

namespace PHPRealCoverage\Model;

use PHPUnit_Framework_TestCase;

class CoveredClassTest extends PHPUnit_Framework_TestCase
{
    public function testToStringReturnsClassContent()
    {
        $line1 = new CoveredLine("line1");
        $line2 = new CoveredLine("line2");
        $line2->setNeccessary(false);
        $line3 = new CoveredLine("line3");

        $class = new CoveredClass();
        $class->addLine(1, $line1);
        $class->addLine(2, $line2);
        $class->addLine(3, $line3);

        $this->assertEquals("line1\n//line2\nline3", (string)$class);
    }
}
