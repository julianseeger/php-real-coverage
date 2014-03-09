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

    /**
     * @param integer $lineNumber
     * @param MutatableLine[] $mutatableLines
     * @param $affectedLines
     */
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
     * @param integer $processedLine
     * @return bool
     */
    private function maxLineReached($mutatableLines, $processedLine)
    {
        return !isset($mutatableLines[$processedLine]);
    }

    /**
     * @param integer $processedLine
     */
    private function increaseMaxLineIfMaxLineIsReached($processedLine)
    {
        if ($processedLine == $this->curentLine) {
            $this->curentLine++;
        }
    }

    /**
     * @param MutatableLine[] $mutatableLines
     * @param integer $requestedLine
     * @return MutatableLine
     * @throws Exception\NoMoreMutationsException
     */
    private function getLine($mutatableLines, $requestedLine)
    {
        if ($this->maxLineReached($mutatableLines, $requestedLine)) {
            throw new NoMoreMutationsException();
        }
        $line = $mutatableLines[$requestedLine];
        return $line;
    }

    public function getProgress()
    {
        return $this->curentLine / count($this->class->getMutatableLines());
    }
}
