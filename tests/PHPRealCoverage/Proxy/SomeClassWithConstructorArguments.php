<?php

namespace PHPRealCoverage\Proxy;

class SomeClassWithConstructorArguments
{
    private $someParameter;

    public function __construct($someParameter)
    {
        $this->someParameter = $someParameter;
    }

    public function getSomeParameter()
    {
        return $this->someParameter;
    }
}
