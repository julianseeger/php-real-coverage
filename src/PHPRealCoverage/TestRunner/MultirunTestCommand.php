<?php

namespace PHPRealCoverage\TestRunner;

class MultirunTestCommand extends \PHPUnit_TextUI_Command
{
    protected function createRunner()
    {
        return new MultirunRunner();
    }
}
