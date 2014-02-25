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

    public function testGetFilteredContentFilteresFinalMethods()
    {
        $line = new CoveredLine("final something ");
        $line->setMethod(true);
        $line->setFinal(true);
        $this->assertEquals("something ", $line->getFilteredContent());
    }

    public function testToStringContentFilteresFinalMethods()
    {
        $line = new CoveredLine("final something ");
        $line->setMethod(true);
        $line->setFinal(true);
        $this->assertEquals("something ", (string)$line);
    }

    public function testGetFilteredContentOnlyFiltersMethods()
    {
        $line = new CoveredLine("final something ");
        $line->setMethod(false);
        $line->setFinal(false);
        $this->assertEquals("final something ", $line->getFilteredContent());
    }

    public function testGetFilteredContentCommentsOut()
    {
        $line = new CoveredLine("final something ");
        $line->setNeccessary(false);
        $line->setFinal(true);
        $this->assertEquals("//final something ", $line->getFilteredContent());
    }
}
