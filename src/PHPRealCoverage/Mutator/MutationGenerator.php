<?php

namespace PHPRealCoverage\Mutator;

use PHPRealCoverage\Mutator\Exception\NoMoreMutationsException;

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
     * @throws Exception\NoMoreMutationsException
     * @return MutationCommand[]
     */
    public function getMutationStack()
    {
        $affectedLines = array();
        $mutatableLines = $this->class->getMutatableLines();

        for ($i = 0; $i <= $this->curentLine; $i++) {
            if ($this->maxLineReached($mutatableLines, $i)) {
                throw new NoMoreMutationsException();
            }
            $line = $mutatableLines[$i];

            if (!$line->isEnabled()) {
                if ($i == $this->curentLine) {
                    $this->curentLine++;
                }
                continue;
            }

            $affectedLines[] = new DefaultMutationCommand($line);
        }
        $this->curentLine++;
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
