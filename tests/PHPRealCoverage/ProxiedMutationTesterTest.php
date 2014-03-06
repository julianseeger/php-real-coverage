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
        $proxy->expects($this->at(0))->method('loadClass')->with($class)->will($this->returnValue(true));
        $proxy->expects($this->at(1))->method('loadClass')->with($class)->will($this->returnValue(false));
        $runner = $this->getMockBuilder('PHPRealCoverage\TestRunner\PHPUnitRunner')
            ->disableOriginalConstructor()
            ->getMock();
        $runner->expects($this->any())->method('isValid')->will($this->returnValue(true));

        $tester = new ProxiedMutationTester($proxy, $class, $runner);
        $this->assertTrue($tester->isValid());
        $this->assertFalse($tester->isValid());
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
        $proxy->expects($this->any())->method('loadClass')->will($this->returnValue(true));
        $runner = $this->getMockBuilder('PHPRealCoverage\TestRunner\PHPUnitRunner')
            ->disableOriginalConstructor()
            ->getMock();
        $runner->expects($this->once())->method('isValid')->will($this->returnValue($result));

        $tester = new ProxiedMutationTester($proxy, $class, $runner);
        $this->assertEquals($result, $tester->isValid());
    }
}
