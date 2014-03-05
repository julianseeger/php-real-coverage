<?php

namespace PHPRealCoverage\Mutator;

class DefaultMutatableLineTest extends \PHPUnit_Framework_TestCase
{
    public function testEnablingAndDisabling()
    {
        $line = new DefaultMutatableLine();
        $this->assertTrue($line->isEnabled());

        $line->disable();
        $this->assertFalse($line->isEnabled());
        $line->disable();
        $this->assertFalse($line->isEnabled());

        $line->enable();
        $this->assertTrue($line->isEnabled());
        $line->enable();
        $this->assertTrue($line->isEnabled());
    }
}
