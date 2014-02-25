<?php

namespace PHPRealCoverage\Model;

class DynamicClassnameCoveredClass extends CoveredClass
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
}
