<?php

namespace PHPRealCoverage\Parser\Model;

use PHPRealCoverage\Mutator\MutatableClass;
use PHPRealCoverage\Mutator\MutatableLine;
use PHPRealCoverage\Proxy\Line;

class DynamicClassnameCoveredClass extends CoveredClass implements MutatableClass
{
    /**
     * @var DynamicClassnameCoveredLine
     */
    private $classLine = null;

    public function setName($name)
    {
        if (!is_null($this->classLine)) {
            $this->classLine->setClassName($name);
        }
        parent::setName($name);
    }

    public function addLine($lineNumber, Line $line)
    {
        if ($line->isClass()) {
            $line = new DynamicClassnameCoveredLine($line);
            $this->classLine = $line;
        }
        parent::addLine($lineNumber, $line);
    }

    /**
     * @return MutatableLine[]
     */
    public function getMutatableLines()
    {
        $mutatableLines = array();
        foreach ($this->getLines() as $line) {
            if ($line->isCovered()) {
                $mutatableLines[] = $line;
            }
        }
        return $mutatableLines;
    }
}
