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

    private function getAffectedLines($lineCount)
    {
        $affectedLines = array();
        $mutatableLines = $this->class->getMutatableLines();

        $i = 0;
        while (count($affectedLines) <= $lineCount) {
            if ($this->maxLineReached($mutatableLines, $i)) {
                throw new NoMoreMutationsException();
            }
            $line = $mutatableLines[$i++];

            if (!$line->isEnabled()) {
                continue;
            }

            $affectedLines[] = new DefaultMutationCommand($line);
        }
        return $affectedLines;
    }

    /**
     * @param $mutatableLines
     * @param $i
     * @return bool
     */
    private function maxLineReached($mutatableLines, $i)
    {
        return !isset($mutatableLines[$i]);
    }
}
