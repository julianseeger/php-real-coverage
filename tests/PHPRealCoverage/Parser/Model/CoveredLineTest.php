<?php

namespace PHPRealCoverage\Parser\Model;

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

    public function enabledStateDataProvider()
    {
        return array(
            array(false, false, false),
            array(true, false, false),
            array(true, true, true),
            array(false, true, false)
        );
    }

    /**
     * @dataProvider enabledStateDataProvider
     * @param $neccessary
     * @param $covered
     * @param $expectedEnabled
     */
    public function testIsEnabledWhenNeccessaryAndCovered($neccessary, $covered, $expectedEnabled)
    {
        $line = new CoveredLine("");
        $line->setNeccessary($neccessary);
        if ($covered) {
            $line->addCoverage("");
        }
        $this->assertEquals($expectedEnabled, $line->isEnabled());
    }

    public function testEnablingAndDisablingTriggersNeccessaryState()
    {
        $line = new CoveredLine("");
        $line->setNeccessary(true);
        $line->addCoverage("");
        $this->assertTrue($line->isEnabled());

        $line->disable();
        $this->assertFalse($line->isEnabled());
        $this->assertFalse($line->isNeccessary());
        $this->assertTrue($line->isCovered());

        $line->enable();
        $this->assertTrue($line->isEnabled());
        $this->assertTrue($line->isNeccessary());
        $this->assertTrue($line->isCovered());
    }

    public function testConvertsCoverageToBoolean()
    {
        $line = new CoveredLine("");
        $this->assertFalse($line->isCovered());

        $line->addCoverage("bla");
        $this->assertTrue($line->isCovered());
    }
}
