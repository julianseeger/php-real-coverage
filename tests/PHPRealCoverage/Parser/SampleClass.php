<?php

namespace PHPRealCoverage\Parser;

class SampleClass
{
    public function returnTrueAndNotFalse()
    {
        return true;
        return false;
    }

    public final function someFinalFunction()
    {
        return "this content is final";
    }
}
