<?php

namespace PHPRealCoverage\Parser\Model;

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

    public function testGetMethods()
    {
        $line1 = new CoveredLine("line1");
        $line1->setMethod(false);
        $line1->setMethodName("method1");

        $line2 = new CoveredLine("line2");
        $line2->setMethod(true);
        $line2->setMethodName("method2");

        $class = new CoveredClass();
        $class->addLine(1, $line1);
        $class->addLine(2, $line2);

        $this->assertEquals(
            array('method2'),
            $class->getMethods()
        );
    }

    public function testGetLine()
    {
        $line0 = new CoveredLine("line0");
        $line1 = new CoveredLine("line1");

        $class = new CoveredClass();
        $class->addLine(0, $line0);
        $class->addLine(1, $line1);

        $this->assertEquals($line0, $class->getLine(0));
        $this->assertEquals($line1, $class->getLine(1));
    }
}
