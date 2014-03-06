<?php

namespace PHPRealCoverage;

class ProxiedMutationTesterTest extends \PHPUnit_Framework_TestCase
{
    public function testTesterLoadsClassIntoProxy()
    {
        $class = $this->getMockForAbstractClass('PHPRealCoverage\Proxy\ClassMetadata');
        $proxy = $this->getMockBuilder('PHPRealCoverage\Proxy\Proxy')
            ->disableOriginalConstructor()
            ->getMock();
        $proxy->expects($this->exactly(2))->method('loadClass')->with($class);
        $runner = $this->getMockBuilder('PHPRealCoverage\TestRunner\PHPUnitRunner')
            ->disableOriginalConstructor()
            ->getMock();

        $tester = new ProxiedMutationTester($proxy, $class, $runner);
        $tester->isValid();
        $tester->isValid();
    }

    public function executorDataProvider()
    {
        return array(
            array(true),
            array(false)
        );
    }

    /**
     * @dataProvider executorDataProvider
     * @param $result
     */
    public function testTesterExecutesTests($result)
    {
        $class = $this->getMockForAbstractClass('PHPRealCoverage\Proxy\ClassMetadata');
        $proxy = $this->getMockBuilder('PHPRealCoverage\Proxy\Proxy')
            ->disableOriginalConstructor()
            ->getMock();
        $proxy->expects($this->any())->method('loadClass');
        $runner = $this->getMockBuilder('PHPRealCoverage\TestRunner\PHPUnitRunner')
            ->disableOriginalConstructor()
            ->getMock();
        $runner->expects($this->once())->method('isValid')->will($this->returnValue($result));

        $tester = new ProxiedMutationTester($proxy, $class, $runner);
        $this->assertEquals($result, $tester->isValid());
    }
}
