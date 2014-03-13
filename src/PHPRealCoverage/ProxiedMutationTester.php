<?php

namespace PHPRealCoverage;

use PHPRealCoverage\Mutator\MutationTester;
use PHPRealCoverage\Proxy\ClassMetadata;
use PHPRealCoverage\Proxy\ProxyAccessor;
use PHPRealCoverage\TestRunner\PHPUnitRunner;

class ProxiedMutationTester implements MutationTester
{
    /**
     * @var ProxyAccessor
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

    public function __construct(ProxyAccessor $proxy, ClassMetadata $class, PHPUnitRunner $runner)
    {
        $this->class = $class;
        $this->proxy = $proxy;
        $this->runner = $runner;
    }

    public function isValid()
    {
        if (!$this->proxy->loadClass($this->class)) {
            return false;
        }
        return $this->runner->isValid();
    }
}
