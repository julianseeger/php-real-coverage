<?php

namespace PHPRealCoverage\Mutator;

interface MutatableClass
{
    /**
     * @return MutatableLine[]
     */
    public function getMutatableLines();

    /**
     * @return string
     */
    public function __toString();
}
