<?php

namespace PHPRealCoverage\Mutator;

class DefaultMutationCommand implements MutationCommand
{
    /**
     * @var MutatableLine
     */
    private $line;

    public function __construct(MutatableLine $line)
    {
        $this->line = $line;
    }

    public function execute()
    {
        $this->line->disable();
    }

    public function undo()
    {
        $this->line->enable();
    }
}
