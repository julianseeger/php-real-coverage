<?php

namespace PHPRealCoverage\Mutator;

interface MutationCommand
{
    public function execute();

    public function undo();
}
