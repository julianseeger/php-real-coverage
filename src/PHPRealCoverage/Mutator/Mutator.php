<?php

namespace PHPRealCoverage\Mutator;

use PHPRealCoverage\Mutator\Exception\NoMoreMutationsException;

class Mutator
{
    public function testMutations(MutationTester $tester, MutationGenerator $generator)
    {
        try {
            while (true) {
                $commands = $generator->getMutationStack();
                foreach ($commands as $command) {
                    $command->execute();
                }

                if (!$tester->isValid()) {
                    foreach ($commands as $command) {
                        $command->undo();
                    }
                }
            }
        } catch (NoMoreMutationsException $e) {
        }
    }
}
