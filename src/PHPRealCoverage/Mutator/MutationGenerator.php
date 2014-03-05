<?php

namespace PHPRealCoverage\Mutator;

class MutationGenerator
{
    /**
     * @var MutatableClass
     */
    private $class;

    /**
     * @var int
     */
    private $curentLine = 0;

    public function __construct(MutatableClass $class)
    {
        $this->class = $class;
    }

    /**
     * @return MutationCommand[]
     */
    public function getMutationStack()
    {
        return $this->getAffectedLines($this->curentLine++);
    }

    private function getAffectedLines($currentLine)
    {
        $affectedLines = array();
        $mutatableLines = $this->class->getMutatableLines();
        for ($i = $currentLine; $i >= 0; $i--) {
            $affectedLines[] = new DefaultMutationCommand($mutatableLines[$i]);
        }
        return $affectedLines;
    }
}
