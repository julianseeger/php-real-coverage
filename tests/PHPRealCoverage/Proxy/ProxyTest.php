<?php

namespace PHPRealCoverage\Proxy;

use PHPRealCoverage\Model\CoveredLine;
use PHPRealCoverage\Model\DynamicClassnameCoveredClass;
use PHPRealCoverage\Parser\ClassParser;

class ProxyTest extends \PHPUnit_Framework_TestCase
{
    public function testProxyLoadsTheInitialBaseClass()
    {
        //arrange
        $line1 = new CoveredLine('namespace SomeNamespace;');
        $line2 = new CoveredLine('class ClassA{');
        $line2->setClass(true);
        $line2->setClassName('ClassA');
        $line3 = new CoveredLine('function returnSomething(){');
        $line3->setMethod(true);
        $line3->setMethodName('returnSomething');
        $line4 = new CoveredLine(' return "something"; }');
        $line5 = new CoveredLine('}');

        $exampleClass = new DynamicClassnameCoveredClass(); // implementation of ClassMetadata
        $exampleClass->setName('ClassA');
        $exampleClass->setNamespace('SomeNamespace');
        $exampleClass->addLine(1, $line1);
        $exampleClass->addLine(2, $line2);
        $exampleClass->addLine(3, $line3);
        $exampleClass->addLine(4, $line4);
        $exampleClass->addLine(5, $line5);

        //act
        $proxy = new Proxy($exampleClass);
        $instance = new \SomeNamespace\ClassA();
    }

    public function testProxyCanExchangeTheBehavior()
    {
        // arrange
        $behavior1 = "namespace ProxyTest;
class ClassA {
    public function returnSomething()
    {
        return 'something';
    }
}";
        $behavior2 = "namespace ProxyTest;
class ClassA {
    public function returnSomething()
    {
        return 'anything';
    }
}";
        $parser = new ClassParser();
        $class1 = $parser->parseString($behavior1);
        $class2 = $parser->parseString($behavior2);

        // act
        $proxy = new Proxy($class1);
        $name = "\\ProxyTest\\ClassA";
        $instance = new $name();
        $this->assertEquals('something', $instance->returnSomething());

        $proxy->loadClass($class2);

        //assert
        $this->assertEquals("anything", $instance->returnSomething());
    }
}
