<?php

namespace PHPRealCoverage;

use PHPRealCoverage\Parser\ClassParser;
use PHPRealCoverage\Proxy\ClassMetadata;
use PHPRealCoverage\Proxy\Line;

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
            $this->populateLine($line, $tests);
        }
    }

    public function parseReport(\PHP_CodeCoverage $report)
    {
        $classes = array();
        foreach ($report->getData() as $filename => $coverage) {
            $classes[] = $this->parseClass($filename, $coverage);
        }
        return $classes;
    }

    /**
     * @param Line $line
     * @param array $tests
     */
    public function populateLine(Line $line, array $tests = null)
    {
        $line->setExecutable(!is_null($tests));

        if (is_null($tests)) {
            return;
        }
        foreach ($tests as $test) {
            $line->addCoverage($test);
        }
    }
}
