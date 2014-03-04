<?php

namespace PHPRealCoverage\Parser\Model;

class DynamicClassnameCoveredClassTest extends \PHPUnit_Framework_TestCase
{
    public function testClassnameIsDynamic()
    {
        // arrange
        $class = new DynamicClassnameCoveredClass();
        $class->setName('ClassA');

        $line1 = new CoveredLine('class ClassA extends Something implements AnInterface');
        $line2 = new CoveredLine('{ function ClassA() {} }');
        $line1->setClass(true);
        $line1->setClassName('ClassA');

        $class->addLine(1, $line1);
        $class->addLine(2, $line2);

        // act
        $class->setName('ClassB');
        $classAsString = (string)$class;

        // assert
        $this->assertEquals(
            "class ClassB extends Something implements AnInterface
{ function ClassA() {} }",
            $classAsString
        );
    }
}
