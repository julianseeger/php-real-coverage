<?php

namespace PHPRealCoverage;

use PHPRealCoverage\Mutator\MutationTester;
use PHPRealCoverage\Proxy\ClassMetadata;
use PHPRealCoverage\Proxy\Proxy;

class ProxiedMutationTester implements MutationTester
{
    /**
     * @var Proxy
     */
    private $proxy;
    /**
     * @var ClassMetadata
     */
    private $class;

    public function __construct(Proxy $proxy, ClassMetadata $class)
    {
        $this->class = $class;
        $this->proxy = $proxy;
    }

    public function isValid()
    {
        $this->proxy->loadClass($this->class);
    }
}
