<?php

namespace PHPRealCoverage\Mutator;

interface MutationCommand
{
    /**
     * @return void
     */
    public function execute();

    /**
     * @return void
     */
    public function undo();
}
