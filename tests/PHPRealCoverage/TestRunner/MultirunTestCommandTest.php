<?php

namespace PHPRealCoverage\TestRunner;

class MultirunTestCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testMultirunTestCommandCreatesMultirunTestRunner()
    {
        $multirunTestCommand = new MultirunTestCommand();

        $createRunnerMethod = new \ReflectionMethod(
            'PHPRealCoverage\TestRunner\MultirunTestCommand',
            'createRunner'
        );
        $createRunnerMethod->setAccessible(true);
        $runner = $createRunnerMethod->invoke($multirunTestCommand);
        $this->assertInstanceOf('PHPRealCoverage\TestRunner\MultirunRunner', $runner);
    }

    public function testCommandCanRunTwice()
    {
        $command = new MultirunTestCommand();
        $this->assertRegexp('/OK \(1 test/', $this->getRunOutput($command));
        $this->assertRegexp('/OK \(1 test/', $this->getRunOutput($command));
    }

    /**
     * @param MultirunTestCommand $command
     * @return string
     */
    private function getRunOutput(MultirunTestCommand $command)
    {
        ob_start();
        $command->run(array('fixture', 'fixture'), false);
        return ob_get_clean();
    }
}
