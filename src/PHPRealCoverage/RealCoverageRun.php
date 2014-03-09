<?php

namespace PHPRealCoverage;

use PHPRealCoverage\Mutator\MutationGenerator;
use PHPRealCoverage\Mutator\Mutator;
use PHPRealCoverage\Proxy\ClassMetadata;
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
        $testRunner = new PHPUnitRunner(new MultirunTestCommand(), array('tests', 'tests'));
        $writer = new RealCoverageModifier($report);

        $classCounter = 0;
        /** @var ClassMetadata $class */
        foreach ($classes as $class) {
            echo (int)(++$classCounter * 100 / count($classes)) . "%: Processing " . $class->getName() . "\r";
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

        echo "\n\nWriting coverage report to " . $output . "\n";
        $htmlWriter = new \PHP_CodeCoverage_Report_HTML();
        $htmlWriter->process($report, $output);
    }
}
