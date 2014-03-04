<?php

namespace PHPRealCoverage\TestRunner;

class MultirunRunnerTest extends \PHPUnit_Framework_TestCase
{
    public function testGetTestWorksTwice()
    {
        $runner = new MultirunRunner();

        $suite = $runner->getTest('fixture', '', array('Test.php', '.phpt'));
        $this->assertEquals(1, $suite->count());

        $suite = $runner->getTest('fixture', '', array('Test.php', '.phpt'));
        $this->assertEquals(1, $suite->count());
    }

    public function testMultipleInstancesUseSameCache()
    {
        $runner = new MultirunRunner();
        $suite = $runner->getTest('fixture', '', array('Test.php', '.phpt'));
        $this->assertEquals(1, $suite->count());

        $runner = new MultirunRunner();
        $suite = $runner->getTest('fixture', '', array('Test.php', '.phpt'));
        $this->assertEquals(1, $suite->count());
    }
}
