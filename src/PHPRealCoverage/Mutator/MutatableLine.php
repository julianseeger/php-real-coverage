<?php

namespace PHPRealCoverage\Mutator;

interface MutatableLine
{
    /**
     * @return void
     */
    public function enable();

    /**
     * @return void
     */
    public function disable();

    /**
     * @return bool
     */
    public function isEnabled();
}
