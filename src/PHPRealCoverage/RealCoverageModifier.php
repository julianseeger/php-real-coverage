<?php

namespace PHPRealCoverage;

use PHPRealCoverage\Proxy\ClassMetadata;
use PHPRealCoverage\Proxy\Line;

class RealCoverageModifier
{
    /**
     * @var \PHP_CodeCoverage
     */
    private $report;

    public function __construct(\PHP_CodeCoverage $report)
    {
        $this->report = $report;
    }

    public function write(ClassMetadata $class)
    {
        $property = new \ReflectionProperty('\PHP_CodeCoverage', 'data');
        $property->setAccessible(true);
        $data = $property->getValue($this->report);

        $data = $this->modifyData($class, $data);

        $property->setValue($this->report, $data);
    }

    private function modifyData(ClassMetadata $class, $data)
    {
        foreach ($class->getLines() as $lineNumber => $line) {
            $data = $this->modifyClassData($class, $lineNumber, $line, $data);
        }
        return $data;
    }

    /**
     * @param integer $lineNumber
     */
    private function modifyClassData(ClassMetadata $class, $lineNumber, Line $line, $data)
    {
        $coverageInformation = $this->generateCoverageInformation($line);
        $data[$class->getFilename()][$lineNumber] = $coverageInformation;
        return $data;
    }

    private function generateCoverageInformation(Line $line)
    {
        if (!$line->isExecutable()) {
            return null;
        }
        if (!$line->isNeccessary()) {
            return array();
        }

        return $line->getCoverage();
    }
}
