<?php

namespace PHPRealCoverage\Proxy;

interface ClassMetadata
{
    public function getName();
    public function setName($name);
    public function getNamespace();

    public function getLines();
    public function __toString();
}
