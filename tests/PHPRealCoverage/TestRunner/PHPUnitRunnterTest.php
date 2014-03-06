<?php

namespace PHPRealCoverage\TestRunner;

class PHPUnitRunnterTest extends \PHPUnit_Framework_TestCase
{
    public function testPHPUnitRunnerPassesArguments()
    {
        // arrange
        $args = array('arg1', 'arg2');

        $runner = $this->getMockBuilder('PHPRealCoverage\TestRunner\MultirunTestCommand')
            ->disableOriginalConstructor()
            ->getMock();
        $runner->expects($this->once())
            ->method('run')
            ->with(array('arg1', 'arg2'), false);

        $tester = new PHPUnitRunner($runner, $args);
        $tester->isValid();
    }

    public function commandResultDataProvider()
    {
        return array(
            array(0, true),
            array(1, false),
            array(2, false)
        );
    }

    /**
     * @dataProvider commandResultDataProvider
     */
    public function testMutataionTesterInterpretsResult($exitCode, $valid)
    {
        // arrange
        $runner = $this->getMockBuilder('PHPRealCoverage\TestRunner\MultirunTestCommand')
            ->disableOriginalConstructor()
            ->getMock();
        $runner->expects($this->once())
            ->method('run')
            ->will($this->returnValue($exitCode));

        $tester = new PHPUnitRunner($runner, array());
        $this->assertEquals($valid, $tester->isValid());
    }
}
