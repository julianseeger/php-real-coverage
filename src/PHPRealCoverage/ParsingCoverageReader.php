<?php

namespace PHPRealCoverage;

use PHPRealCoverage\Parser\ClassParser;
use PHPRealCoverage\Parser\Exception\ParserException;
use PHPRealCoverage\Proxy\ClassMetadata;
use PHPRealCoverage\Proxy\Line;

class ParsingCoverageReader
{
    /**
     * @var ClassParser
     */
    private $parser;

    public function __construct(ClassParser $parser = null)
    {
        if ($parser == null) {
            $parser = new ClassParser();
        }
        $this->parser = $parser;
    }

    public function parseClass($filename, array $coverageData)
    {
        $coveredClass = $this->parser->parse($filename);
        $this->addCoverage($coveredClass, $coverageData);
        return $coveredClass;
    }

    public function addCoverage(ClassMetadata $coveredClass, $coverageData)
    {
        foreach ($coverageData as $lineNumber => $tests) {
            $line = $coveredClass->getLine($lineNumber);
            if (is_null($line)) {
                throw new ParserException("Failed to find line " . $lineNumber . " in " . $coveredClass->getFilename());
            }
            $this->populateLine($line, $tests);
        }
    }

    public function parseReport(\PHP_CodeCoverage $report)
    {
        $classes = array();
        foreach ($report->getData() as $filename => $coverage) {
            try {
                $classes[] = $this->parseClass($filename, $coverage);
            } catch (ParserException $e) {
                echo "Skipping class " . $filename . ", failed to parse";
            }
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
