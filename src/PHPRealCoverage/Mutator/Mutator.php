<?php

namespace PHPRealCoverage\Mutator;

use PHPRealCoverage\Mutator\Exception\NoMoreMutationsException;

class Mutator
{
    public function testMutations(MutationTester $tester, MutationGenerator $generator)
    {
        try {
            while (true) {
                $this->iterate($tester, $generator);
            }
        } catch (NoMoreMutationsException $e) {
            // stop when all permutations have been tested
        }
    }

    /**
     * @param MutationCommand[] $commands
     * @param MutationTester $tester
     * @return bool
     */
    private function findWorkingPermutations(array $commands, MutationTester $tester)
    {
        if (empty($commands)) {
            return false;
        }

        $lineToTest = $commands[0];
        $lineToTest->execute();
        if ($tester->isValid()) {
            return true;
        }

        $rest = array_slice($commands, 1);
        if ($this->findWorkingPermutations($rest, $tester)) {
            return true;
        }

        $lineToTest->undo();
        return $this->findWorkingPermutations($rest, $tester);
    }

    /**
     * @param MutationTester $tester
     * @param MutationGenerator $generator
     */
    public function iterate(MutationTester $tester, MutationGenerator $generator)
    {
        $commands = $generator->getMutationStack();
        $lineToTest = $commands[0];
        $lineToTest->execute();

        $rest = array_slice($commands, 1);
        $this->findWorkingPermutations($rest, $tester);
        if (!$tester->isValid()) {
            foreach ($commands as $command) {
                $command->undo();
            }
        }
    }
}
