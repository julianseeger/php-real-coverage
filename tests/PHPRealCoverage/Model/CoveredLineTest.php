<?php

namespace PHPRealCoverage\Model;

use PHPUnit_Framework_TestCase;

class CoveredLineTest extends PHPUnit_Framework_TestCase
{
    public function testToStringReturnsContent()
    {
        $line = new CoveredLine(" yep something ");
        $this->assertEquals(" yep something ", (string)$line);
    }

    public function testToStringReturnsCommentedOutContentIfNotNeccessary()
    {
        $line = new CoveredLine(" yep something ");
        $line->setNeccessary(false);
        $this->assertEquals("// yep something ", (string)$line);
    }
}
