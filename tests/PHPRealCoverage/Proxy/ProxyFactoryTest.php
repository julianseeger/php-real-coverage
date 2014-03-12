<?php

namespace PHPRealCoverage\Proxy;

use PHPRealCoverage\Parser\Model\CoveredClass;
use PHPRealCoverage\Parser\Model\CoveredLine;
use PHPRealCoverage\Parser\Model\DynamicClassnameCoveredClass;

class ProxyFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testSupportsClassKnowsWhichClassesAreSupported()
    {
        // arrange
        $class = new CoveredClass();
        $class->setNamespace("ImagineryNamespace");
        $class->setName("SupportedClass");

        // act
        $factory = new ProxyFactory(array($class));

        // assert
        $this->assertTrue($factory->supports('\ImagineryNamespace\SupportedClass'));
        $this->assertFalse($factory->supports('\ImagineryNamespace\UnsupportedClass'));
    }

    /**
     * @expectedException \PHPRealCoverage\Proxy\UnsupportedClassException
     */
    public function testGetProxyThrowsAnExceptionIfClassIsNotSupported()
    {
        $factory = new ProxyFactory(array());
        $factory->getProxy('\Not\Existing');
    }

    public function testGetProxyCreatesProxy()
    {
        // arrange
        $class = $this->createFakeClass("ImaginaryNamespace", "SupportedClass");
        $factory = new ProxyFactory(array($class));

        // act
        $proxy = $factory->getProxy('\ImaginaryNamespace\SupportedClass');

        // assert
        $this->assertInstanceOf('PHPRealCoverage\Proxy\Proxy', $proxy);
        $class = new \ImaginaryNamespace\SupportedClass();
        $class->__PROXYcheckInstance(); //only available for proxies
    }

    public function testFactoryCachesProxiesAndReturnsAlwaysTheSameInstance()
    {
        // arrange
        $class = $this->createFakeClass("ImaginaryNamespace", "ClassA");
        $factory = new ProxyFactory(array($class));

        // act
        $proxy1 = $factory->getProxy('\ImaginaryNamespace\ClassA');
        $proxy2 = $factory->getProxy('\ImaginaryNamespace\ClassA');

        // assert
        $this->assertSame($proxy1, $proxy2);
    }

    /**
     * @param $namespace
     * @param $classname
     * @return CoveredClass
     */
    private function createFakeClass($namespace, $classname)
    {
        $line0 = new CoveredLine("namespace " . $namespace . ";");
        $line1 = new CoveredLine("class " . $classname . "{");
        $line1->setClassName($classname);
        $line1->setClass(true);
        $line2 = new CoveredLine("}");
        $class = new DynamicClassnameCoveredClass();
        $class->setName($classname);
        $class->addLine(0, $line0);
        $class->addLine(1, $line1);
        $class->addLine(2, $line2);
        $class->setNamespace($namespace);
        return $class;
    }
}
