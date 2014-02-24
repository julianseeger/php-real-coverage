<?php

namespace PHPRealCoverage\Parser;

class SampleClass
{
    public function returnTrueAndNotFalse()
    {
        return true;
        return false;
    }

    final public function someFinalFunction()
    {
        return "this content is final";
    }
}
