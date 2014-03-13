<?php

namespace PHPRealCoverage;

use PHPRealCoverage\Mutator\MutationGenerator;
use PHPRealCoverage\Mutator\MutationTester;
use PHPRealCoverage\Mutator\Mutator;
use PHPRealCoverage\Proxy\ClassMetadata;
use PHPRealCoverage\Proxy\ProxyFactory;
use PHPRealCoverage\TestRunner\MultirunTestCommand;
use PHPRealCoverage\TestRunner\PHPUnitRunner;

class RealCoverageRun
{
    public function run($input, $output = 'real-coverage-html')
    {
        $report = unserialize(file_get_contents($input));
        $reader = new ParsingCoverageReader();
        $classes = $reader->parseReport($report);
        $mutator = new Mutator();
        $testRunner = new PHPUnitRunner(new MultirunTestCommand(), array('tests', 'tests'));
        $writer = new RealCoverageModifier($report);

        $proxyFactory = new ProxyFactory($classes);

        $classCounter = 0;
        /** @var ClassMetadata $class */
        foreach ($classes as $class) {
            if (!$class->isCovered()) {
                continue;
            }
            echo "\n" . (int)(++$classCounter * 100 / count($classes)) . "%: Processing " . $class->getName() . "\n";

            $this->calculateRealCoverage(
                $class,
                $proxyFactory,
                $testRunner,
                $mutator
            );

            $writer->write($class);
        }

        echo "\n\nWriting coverage report to " . $output . "\n";
        $htmlWriter = new \PHP_CodeCoverage_Report_HTML();
        $htmlWriter->process($report, $output);
    }

    /**
     * @param $class
     * @param $proxyFactory
     * @param $testRunner
     * @param $mutator
     * @throws \Exception
     */
    private function calculateRealCoverage(
        $class,
        ProxyFactory $proxyFactory,
        PHPUnitRunner $testRunner,
        Mutator $mutator
    ) {
        $proxy = $proxyFactory->getProxy($class);
        $tester = new ProxiedMutationTester($proxy, $class, $testRunner);
        $this->testPrecondition($tester);

        $mutator->testMutations($tester, new MutationGenerator($class));
        $proxy->loadClass($class);

        $this->testPostcondition($tester);
    }

    /**
     * @param $tester
     * @throws \Exception
     */
    private function testPrecondition(MutationTester $tester)
    {
        if (!$tester->isValid()) {
            throw new \Exception("Tester did not reach a valid state before mutation");
        }
    }

    /**
     * @param $tester
     * @throws \Exception
     */
    private function testPostcondition(MutationTester $tester)
    {
        if (!$tester->isValid()) {
            throw new \Exception("Tester did not reach a valid state after mutation");
        }
    }
}
