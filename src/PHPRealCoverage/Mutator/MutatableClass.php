<?php

namespace PHPRealCoverage\Mutator;

interface MutatableClass
{
    /**
     * @return MutatableLine[]
     */
    public function getMutatableLines();
}
