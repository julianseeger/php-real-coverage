<?php

namespace PHPRealCoverage\Parser;

class ClassParserTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPRealCoverage\Parser\ClassParser */
    private $parser;

    protected function setUp()
    {
        parent::setUp();

        $this->parser = new ClassParser();
    }

    public function testParse()
    {
        $parsedClass = $this->parser->parse(__DIR__ . '/SampleClass.php');
        $this->assertEquals("SampleClass", $parsedClass->getName());
    }

    public function testParseString()
    {
        $content = file_get_contents(__DIR__ . '/SampleClass.php');
        $parsedClass = $this->parser->parseString($content);
        $this->assertEquals("SampleClass", $parsedClass->getName());
    }

    public function testParserDetectsNamespace()
    {
        $class = $this->parser->parse(__DIR__ . '/SampleClass.php');
        $this->assertEquals("PHPRealCoverage\\Parser", $class->getNamespace());
    }

    public function parseClassNameDataProvider()
    {
        return array (
            array (
                "\npublic class Defaultname extends Supersonic implements \\Namespace\\Interface {",
                "Defaultname"
            ),
            array(
                "class AnotherStudlyCaps\n{",
                "AnotherStudlyCaps"
            ),
            array(
                "private class PrivateClass{",
                "PrivateClass"
            ),
            array(
                "protected class PrivateClass\n{",
                "PrivateClass"
            ),
            array(
                "/**
                  * This class has a comment
                  */
                 class CommentedOne\n{",
                "CommentedOne"
            ),
            array(
                "//class FakeClass\n
                 class CommentedOne\n{",
                "CommentedOne"
            )
        );
    }

    /**
     * @dataProvider parseClassNameDataProvider
     */
    public function testParseClassNameForDifferentClasses($class, $name)
    {
        $this->assertEquals($name, $this->parser->parseName($class));
    }

    public function testParseLine()
    {
        $input = "    public function something()";
        $line = $this->parser->parseLine($input);

        $this->assertEquals($input, $line->getContent());
    }

    public function parseLineDetectsClassDataProvider()
    {
        return array(
            array('nada', false, null),
            array('class someclass', true, 'someclass'),
            array(' class someclass ', true, 'someclass'),
            array('* class something', false, null),
            array('class someclass{', true, 'someclass')
        );
    }

    /**
     * @dataProvider parseLineDetectsClassDataProvider
     * @param $input
     * @param $isClass
     */
    public function testParseLineDetectsClass($input, $isClass, $className)
    {
        $line = $this->parser->parseLine($input);
        $this->assertEquals($isClass, $line->isClass());
        $this->assertEquals($className, $line->getClassName());
    }

    public function parseLineDataProvider()
    {
        return array(
            array("", false),
            array("public function someMethod()", true, "someMethod"),
            array("private function someNonPSRMethod(){", true, "someNonPSRMethod"),
            array(" protected function someSpaces() { ", true, "someSpaces"),
            array("final public static function someOverkill()", true, "someOverkill", true)
        );
    }

    /**
     * @dataProvider parseLineDataProvider
     * @param $input
     * @param $method
     * @param $isMethod
     * @param $isFinal
     */
    public function testParseLineDetectsMethods($input, $isMethod)
    {
        $line = $this->parser->parseLine($input);
        $this->assertEquals($isMethod, $line->isMethod());
    }

    /**
     * @dataProvider parseLineDataProvider
     * @param $input
     * @param $method
     * @param $isMethod
     * @param $isFinal
     */
    public function testParseLineDetectsFinals($input, $isMethod, $method = "", $isFinal = false)
    {
        $line = $this->parser->parseLine($input);
        $this->assertEquals($isFinal, $line->isFinal());
    }

    /**
     * @dataProvider parseLineDataProvider
     * @param $input
     * @param $isMethod
     * @param string $method
     */
    public function testParseLineDetectsMethodName($input, $isMethod, $method = "")
    {
        $line = $this->parser->parseLine($input);
        $this->assertEquals($method, $line->getMethodName());
    }

    public function testParserAsComponent()
    {
        $class = $this->parser->parse(__DIR__ . '/SampleClass.php');
        $lines = $class->getLines();
        $this->assertEquals(18, count($lines));

        $this->assertEquals("SampleClass", $class->getName());
        $this->assertEquals("class SampleClass", $lines[5]->getContent());
        $this->assertTrue($lines[5]->isClass());
        $this->assertTrue($lines[7]->isMethod());
        $this->assertTrue($lines[13]->isMethod());
        $this->assertFalse($lines[7]->isFinal());
        $this->assertTrue($lines[13]->isFinal());
    }

    public function testParserAsComponentReturnsDynamicComponents()
    {
        $class = $this->parser->parse(__DIR__ . '/SampleClass.php');
        $lines = $class->getLines();
        $classLine = $lines[5];
        $this->assertInstanceOf('PHPRealCoverage\Parser\Model\DynamicClassnameCoveredLine', $classLine);

        $class->setName('Replacement');
        $this->assertEquals('class Replacement', (string)$classLine);
    }
}
