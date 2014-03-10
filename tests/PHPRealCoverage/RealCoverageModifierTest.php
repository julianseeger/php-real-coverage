<?php

namespace PHPRealCoverage;

use PHPRealCoverage\Proxy\ClassMetadata;

class RealCoverageModifierTest extends \PHPUnit_Framework_TestCase
{
    public function testModifierRemovesCoverageFromUnneccessaryLines()
    {
        $coverage = $this->generateCoverageForFixture();

        $reader = new ParsingCoverageReader();
        $classes = $reader->parseReport($coverage);

        /** @var ClassMetadata $class */
        $class = $classes[0];
        $this->assertTrue($class->getLine(8)->isCovered());
        $class->getLine(8)->setNeccessary(false);

        $writer = new RealCoverageModifier($coverage);
        $writer->write($class);

        $classes = $reader->parseReport($coverage);
        $class = $classes[0];
        $this->assertFalse($class->getLine(8)->isCovered());
        $this->assertTrue($class->getLine(9)->isCovered());
        $this->assertFalse($class->getLine(10)->isCovered());

        $this->assertFalse($class->getLine(10)->isExecutable());
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
                    8 => 1,
                    9 => 1
                )
            ),
            'Nonamespace\\ExampleTest::testSomethingVerySpecial',
            true
        );
        return $coverage;
    }
}
