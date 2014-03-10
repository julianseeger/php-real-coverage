<?php

namespace PHPRealCoverage;

use PHPRealCoverage\Parser\Exception\ParserException;
use PHPRealCoverage\Parser\Model\CoveredLine;

class ParsingCoverageReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testParseReportsCreatesClassesWithCoverageInformation()
    {
        $reader = new ParsingCoverageReader();
        $classes = $reader->parseReport($this->generateCoverageForFixture());
        $this->assertEquals(1, count($classes));
    }

    public function testParseReportsSkippsClassesWithParserExceptions()
    {
        $parser = $this->getMock('PHPRealCoverage\Parser\ClassParser');
        $parser->expects($this->any())
            ->method('parse')
            ->will($this->throwException(new ParserException()));

        $reader = new ParsingCoverageReader($parser);
        ob_start();
        $classes = $reader->parseReport($this->generateCoverageForFixture());
        ob_end_clean();
        $this->assertEquals(0, count($classes));
    }

    /**
     * @expectedException \PHPRealCoverage\Parser\Exception\ParserException
     * @expectedExceptionMessage Failed to find line 1 in filename
     */
    public function testAddCoverageInterruptsWithVerboseInformationIfLineIsNotFound()
    {
        $class = $this->getMockForAbstractClass('PHPRealCoverage\Proxy\ClassMetadata');
        $class->expects($this->any())
            ->method('getLine')
            ->will($this->returnValue(null));
        $class->expects($this->any())->method('getFilename')->will($this->returnValue('filename'));
        $reader = new ParsingCoverageReader();
        $reader->addCoverage($class, array(1 => array("test")));
    }

    public function testParseClassAddsCoverageInformation()
    {
        $filename = __DIR__ . '/../../fixture/NoNamespace/ExampleTest.php';
        $coverageData = array(9 => array('Nonamespace\\ExampleTest::testSomethingVerySpecial'));

        $reader = new ParsingCoverageReader();
        $class = $reader->parseClass($filename, $coverageData);
        $this->assertInstanceOf('PHPRealCoverage\Parser\Model\DynamicClassnameCoveredClass', $class);

        $lines = $class->getLines();
        $class->setName($class->getName());
        $expectedContent = str_replace('<?php', '', file_get_contents($filename));
        $this->assertEquals($expectedContent, (string)$class);

        $this->assertFalse($lines[7]->isCovered());
        $this->assertFalse($lines[8]->isCovered());
        $this->assertTrue($lines[9]->isCovered());
        $this->assertFalse($lines[10]->isCovered());
        $this->assertFalse($lines[11]->isCovered());
    }

    public function executableInformationDataProvider()
    {
        return array(
            array(null, false),
            array(array(), true),
            array(array("test"), true)
        );
    }

    /**
     * @dataProvider executableInformationDataProvider
     */
    public function testPopulateLineAddsExecutableInformation($tests, $executable)
    {
        $line = new CoveredLine("");
        $reader = new ParsingCoverageReader();
        $reader->populateLine($line, $tests);

        $this->assertEquals($executable, $line->isExecutable());
    }

    /**
     * @return \PHP_CodeCoverage
     */
    public function generateCoverageForFixture()
    {
        $coverage = new \PHP_CodeCoverage(null, null);
        $coverage->append(
            array(
                __DIR__ . '/../../fixture/NoNamespace/ExampleTest.php' => array(
                    9 => 1
                )
            ),
            'Nonamespace\\ExampleTest::testSomethingVerySpecial',
            true
        );
        return $coverage;
    }
}
