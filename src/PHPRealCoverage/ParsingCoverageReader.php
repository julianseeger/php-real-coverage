<?php

namespace PHPRealCoverage;

use PHPRealCoverage\Parser\ClassParser;
use PHPRealCoverage\Proxy\ClassMetadata;

class ParsingCoverageReader
{
    public function parseClass($filename, array $coverageData)
    {
        $parser = new ClassParser();
        $coveredClass = $parser->parse($filename);
        $this->addCoverage($coveredClass, $coverageData);
        return $coveredClass;
    }

    private function addCoverage(ClassMetadata $coveredClass, $coverageData)
    {
        foreach ($coverageData as $lineNumber => $tests) {
            $line = $coveredClass->getLine($lineNumber);
            foreach ($tests as $test) {
                $line->addCoverage($test);
            }
        }
    }

    public function parseReport(\PHP_CodeCoverage $generateCoverageForFixture)
    {
        $classes = array();
        foreach ($generateCoverageForFixture->getData() as $filename => $coverage) {
            $classes[] = $this->parseClass($filename, $coverage);
        }
        return $classes;
    }
}
