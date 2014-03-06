<?php

namespace PHPRealCoverage;

use PHPRealCoverage\Mutator\MutationTester;
use PHPRealCoverage\Proxy\ClassMetadata;
use PHPRealCoverage\Proxy\Proxy;
use PHPRealCoverage\TestRunner\PHPUnitRunner;

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
    /**
     * @var TestRunner\PHPUnitRunner
     */
    private $runner;

    public function __construct(Proxy $proxy, ClassMetadata $class, PHPUnitRunner $runner)
    {
        $this->class = $class;
        $this->proxy = $proxy;
        $this->runner = $runner;
    }

    public function isValid()
    {
        $this->proxy->loadClass($this->class);
        return $this->runner->isValid();
    }
}
