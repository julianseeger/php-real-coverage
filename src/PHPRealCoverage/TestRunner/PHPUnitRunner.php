<?php

namespace PHPRealCoverage\TestRunner;

class PHPUnitRunner
{
    /**
     * @var MultirunTestCommand
     */
    private $testCommand;

    private $args;

    public function __construct(MultirunTestCommand $testCommand, array $args)
    {
        $this->testCommand = $testCommand;
        $this->args = $args;
    }

    public function isValid()
    {
        return $this->testCommand->run($this->args, false) === 0;
    }
}
