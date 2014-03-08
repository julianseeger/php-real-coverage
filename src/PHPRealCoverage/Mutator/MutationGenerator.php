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
            $this->addCommandForLine($i, $mutatableLines, $affectedLines);
        }
        $this->curentLine++;
        return array_reverse($affectedLines);
    }

    public function addCommandForLine($lineNumber, $mutatableLines, &$affectedLines)
    {
        $line = $this->getLine($mutatableLines, $lineNumber);

        if (!$line->isEnabled()) {
            $this->increaseMaxLineIfMaxLineIsReached($lineNumber);
            return;
        }

        $affectedLines[] = new DefaultMutationCommand($line);
    }

    /**
     * @param MutatableLine[] $mutatableLines
     * @param integer $i
     * @return bool
     */
    private function maxLineReached($mutatableLines, $i)
    {
        return !isset($mutatableLines[$i]);
    }

    /**
     * @param $i
     */
    private function increaseMaxLineIfMaxLineIsReached($i)
    {
        if ($i == $this->curentLine) {
            $this->curentLine++;
        }
    }

    /**
     * @param $mutatableLines
     * @param $i
     * @return mixed
     * @throws Exception\NoMoreMutationsException
     */
    private function getLine($mutatableLines, $i)
    {
        if ($this->maxLineReached($mutatableLines, $i)) {
            throw new NoMoreMutationsException();
        }
        $line = $mutatableLines[$i];
        return $line;
    }
}
