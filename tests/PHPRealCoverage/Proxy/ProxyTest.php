<?php

namespace PHPRealCoverage\Proxy;

use PHPRealCoverage\Parser\ClassParser;
use PHPRealCoverage\Parser\Model\CoveredClass;
use PHPRealCoverage\Parser\Model\CoveredLine;
use PHPRealCoverage\Parser\Model\DynamicClassnameCoveredClass;

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
        $proxy->load();
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
        $proxy->load();
        $name = "\\ProxyTest\\ClassA";
        $instance = new $name();
        $this->assertEquals('something', $instance->returnSomething());

        $proxy->loadClass($class2);

        //assert
        $this->assertEquals("anything", $instance->returnSomething());
    }

    public function testLoadClassReturnsFalseOnParseError()
    {
        $class = new CoveredClass();
        $class->setName("MyName");
        $class->addLine(0, new CoveredLine("THIS IS A PARSE ERROR!"));

        $proxy = new Proxy($class);
        $this->assertFalse($proxy->loadClass($class));
    }

    public function testLoadClassReturnsTrueOnParsingSuccess()
    {
        $class = new DynamicClassnameCoveredClass();
        $class->setName("MyOtherName");
        $class->setNamespace("MyNamespace");
        $class->addLine(0, new CoveredLine("namespace MyNamespace;"));
        $line1 = new CoveredLine("class MyOtherName{");
        $line1->setClass(true);
        $line1->setClassName("MyOtherName");
        $class->addLine(1, $line1);
        $class->addLine(2, new CoveredLine("}"));

        $proxy = new Proxy($class);
        $proxy->load();
        $this->assertTrue($proxy->loadClass($class));
    }

    public function canonicalClassnameDataProvider()
    {
        return array(
            array('SomeNamespace', 'SomeClassname', '\\SomeNamespace\\SomeClassname'),
            array('\\SomeNamespace\\', 'SomeClassname', '\\SomeNamespace\\SomeClassname'),
            array('\\SomeNamespace', 'SomeClassname', '\\SomeNamespace\\SomeClassname'),
            array('Namespace', 'Class__WITH__SPECIAL_123chars', '\\Namespace\\Class__WITH__SPECIAL_123chars')
        );
    }

    /**
     * @dataProvider canonicalClassnameDataProvider
     * @param $namespace
     * @param $name
     * @param $expected
     */
    public function testGetCanonicalClassname($namespace, $name, $expected)
    {
        $input = new CoveredClass();
        $input->setNamespace($namespace);
        $input->setName($name);

        $proxy = new Proxy($input);
        $this->assertEquals($expected, $proxy->getCanonicalClassName($input));
    }
}
