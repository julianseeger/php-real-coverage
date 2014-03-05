<?php

namespace PHPRealCoverage\Mutator;

class DefaultMutationCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testExecuteDisablesLine()
    {
        $line = $this->getMockForAbstractClass('PHPRealCoverage\Mutator\MutatableLine');
        $line->expects($this->once())->method('disable');
        $line->expects($this->never())->method('enable');

        $command = new DefaultMutationCommand($line);
        $command->execute();
    }

    public function testUndoEnablesLine()
    {
        $line = $this->getMockForAbstractClass('PHPRealCoverage\Mutator\MutatableLine');
        $line->expects($this->once())->method('enable');
        $line->expects($this->never())->method('disable');

        $command = new DefaultMutationCommand($line);
        $command->undo();
    }
}
