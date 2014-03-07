<?php

namespace PHPRealCoverage;

use PHPRealCoverage\Parser\Model\CoveredLine;

class ParsingCoverageReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testParseReportsCreatesClassesWithCoverageInformation()
    {
        $reader = new ParsingCoverageReader();
        $classes = $reader->parseReport($this->generateCoverageForFixture());
        $this->assertEquals(1, count($classes));
    }

    public function testParseClassAddsCoverageInformation()
    {
        $filename = __DIR__ . '/../../fixture/ExampleTest.php';
        $coverageData = array(9 => array('Nonamespace\\ExampleTest::testSomethingVerySpecial'));

        $reader = new ParsingCoverageReader();
        $class = $reader->parseClass($filename, $coverageData);
        $this->assertInstanceOf('PHPRealCoverage\Parser\Model\DynamicClassnameCoveredClass', $class);

        $lines = $class->getLines();
        $class->setName($class->getName());
        $this->assertEquals(file_get_contents($filename), (string)$class);

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
                __DIR__ . '/../../fixture/ExampleTest.php' => array(
                    9 => 1
                )
            ),
            'Nonamespace\\ExampleTest::testSomethingVerySpecial',
            true
        );
        return $coverage;
    }
}
