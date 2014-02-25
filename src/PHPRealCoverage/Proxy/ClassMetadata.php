<?php

namespace PHPRealCoverage\Proxy;

interface ClassMetadata
{
    public function getName();
    public function getNamespace();
    public function __toString();
}
