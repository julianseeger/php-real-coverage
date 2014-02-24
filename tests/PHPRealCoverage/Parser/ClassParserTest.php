<?php

namespace PHPRealCoverage\Parser;

class ClassParserTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPRealCoverage\Parser\ClassParser */
    private $parser;

    protected function setUp()
    {
        parent::setUp();

        $this->parser = new ClassParser(__DIR__ . '/SampleClass.php');
    }

    public function testParse()
    {
        $parsedClass = $this->parser->parse();
        $this->assertEquals("SampleClass", $parsedClass->getName());
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
    public function testParseLineDetectsMethods($input, $isMethod, $method = "", $isFinal = false)
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
        $class = $this->parser->parse();
        $lines = $class->getLines();
        $this->assertEquals(18, count($lines));

        $this->assertEquals("SampleClass", $class->getName());
        $this->assertEquals("class SampleClass", $lines[5]->getContent());
        $this->assertTrue($lines[7]->isMethod());
        $this->assertTrue($lines[13]->isMethod());
        $this->assertFalse($lines[7]->isFinal());
        $this->assertTrue($lines[13]->isFinal());
    }
}
