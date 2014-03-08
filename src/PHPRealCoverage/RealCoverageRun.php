<?php

namespace PHPRealCoverage;

use PHPRealCoverage\Mutator\MutationGenerator;
use PHPRealCoverage\Mutator\Mutator;
use PHPRealCoverage\Proxy\Proxy;
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
        $testRunner = new PHPUnitRunner(new MultirunTestCommand(), array());
        $writer = new RealCoverageModifier($report);

        foreach ($classes as $class) {
            $proxy = new Proxy($class);
            $proxy->load();
            $tester = new ProxiedMutationTester($proxy, $class, $testRunner);
            if (!$tester->isValid()) {
                throw new \Exception("Tester did not reach a valid state before mutation");
            }
            $mutator->testMutations($tester, new MutationGenerator($class));
            $proxy->loadClass($class);
            if (!$tester->isValid()) {
                throw new \Exception("Tester did not reach a valid state after mutation");
            }

            $writer->write($class);
        }

        $htmlWriter = new \PHP_CodeCoverage_Report_HTML();
        $htmlWriter->process($report, $output);
    }
}
