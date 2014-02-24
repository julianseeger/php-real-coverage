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
}
