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
        }
    }

    /**
     * @param MutationCommand[] $commands
     * @param MutationTester $tester
     * @return bool
     */
    private function findWorkingPermutation(array $commands, MutationTester $tester)
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
        if ($this->findWorkingPermutation($rest, $tester)) {
            return true;
        }

        $lineToTest->undo();
        return $this->findWorkingPermutation($rest, $tester);
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

        if (!$tester->isValid()) {
            $rest = array_slice($commands, 1);
            if (!$this->findWorkingPermutation($rest, $tester)) {
                foreach ($commands as $command) {
                    $command->undo();
                }
            }
        }
    }
}
