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
        $proxy->expects($this->exactly(2))
            ->method('loadClass')
            ->with($class);

        $tester = new ProxiedMutationTester($proxy, $class);
        $tester->isValid();
        $tester->isValid();
    }
}
