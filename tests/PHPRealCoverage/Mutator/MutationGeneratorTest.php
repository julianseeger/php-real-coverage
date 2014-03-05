<?php

namespace PHPRealCoverage\Mutator;

class MutationGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DefaultMutatableLine
     */
    private $line1;
    /**
     * @var DefaultMutatableLine
     */
    private $line2;
    /**
     * @var DefaultMutatableLine
     */
    private $line3;
    /**
     * @var MutationGenerator
     */
    private $mutator;

    protected function setUp()
    {
        $this->line1 = new DefaultMutatableLine();
        $this->line2 = new DefaultMutatableLine();
        $this->line3 = new DefaultMutatableLine();

        $class = $this->getMockForAbstractClass('PHPRealCoverage\Mutator\MutatableClass');
        $class->expects($this->any())
            ->method('getMutatableLines')
            ->will($this->returnValue(array($this->line1, $this->line2, $this->line3)));

        $this->mutator = new MutationGenerator($class);
    }

    /**
     * @param MutationCommand $command
     * @return MutatableLine
     */
    private function getLine($command)
    {
        $this->assertInstanceOf('PHPRealCoverage\Mutator\MutationCommand', $command);
        $property = new \ReflectionProperty('PHPRealCoverage\Mutator\DefaultMutationCommand', 'line');
        $property->setAccessible(true);
        return $property->getValue($command);
    }

    public function testMutatorCreatesMutationStackForLine1()
    {
        $stack1 = $this->mutator->getMutationStack();
        $this->assertEquals(1, count($stack1));
        $this->assertEquals($this->line1, $this->getLine($stack1[0]));
    }

    public function testMutatorCreatesMutationStackForLine2()
    {
        $this->mutator->getMutationStack();
        $stack2 = $this->mutator->getMutationStack();
        $this->assertEquals(2, count($stack2));
        $this->assertEquals($this->line2, $this->getLine($stack2[0]));
        $this->assertEquals($this->line1, $this->getLine($stack2[1]));
    }

    public function testMutatorCreatesMutationStackForLine3()
    {
        $this->mutator->getMutationStack();
        $this->mutator->getMutationStack();
        $stack3 = $this->mutator->getMutationStack();
        $this->assertEquals(3, count($stack3));
        $this->assertEquals($this->line3, $this->getLine($stack3[0]));
        $this->assertEquals($this->line2, $this->getLine($stack3[1]));
        $this->assertEquals($this->line1, $this->getLine($stack3[2]));
    }

    public function testMutatorOnlyUsesEnabledLines()
    {
        $this->line2->disable();

        $this->mutator->getMutationStack();
        $stack2 = $this->mutator->getMutationStack();
        $this->assertEquals(2, count($stack2));
        $this->assertEquals($this->line3, $this->getLine($stack2[0]));
        $this->assertEquals($this->line1, $this->getLine($stack2[1]));
    }

    public function testMutatorThrowsExceptionAfterLastStack()
    {
        $this->line2->disable();
        $this->line3->disable();

        $this->mutator->getMutationStack();
        try {
            $this->mutator->getMutationStack();
        } catch (NoMoreMutationsException $e) {

        }
    }
}
