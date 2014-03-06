<?php

namespace PHPRealCoverage\Mutator;

use PHPRealCoverage\Mutator\Exception\NoMoreMutationsException;

class MutatorTest extends \PHPUnit_Framework_TestCase
{
    public function testMutatorMutatesLine()
    {
        // arrange
        $tester = $this->getMockForAbstractClass('PHPRealCoverage\Mutator\MutationTester');
        $tester->expects($this->any())
            ->method('isValid')
            ->will($this->returnValue(true));

        $line = new DefaultMutatableLine();
        $command = new DefaultMutationCommand($line);
        $generator = $this->getMockBuilder('PHPRealCoverage\Mutator\MutationGenerator')
            ->disableOriginalConstructor()
            ->getMock();
        $generator->expects($this->at(0))
            ->method('getMutationStack')
            ->will($this->returnValue(array($command)));
        $generator->expects($this->at(1))
            ->method('getMutationStack')
            ->will($this->throwException(new NoMoreMutationsException()));

        // act
        $mutator = new Mutator();
        $mutator->testMutations($tester, $generator);

        // assert
        $this->assertFalse($line->isEnabled());
    }

    public function testMutatorMutatesClass()
    {
        // arrange
        $line1 = new DefaultMutatableLine();
        $line2 = new DefaultMutatableLine();
        $line3 = new DefaultMutatableLine();

        $class = $this->getMockForAbstractClass('PHPRealCoverage\Mutator\MutatableClass');
        $class->expects($this->any())
            ->method('getMutatableLines')
            ->will($this->returnValue(array($line1, $line2, $line3)));
        $generator = new MutationGenerator($class);

        $tester = $this->getMockForAbstractClass('PHPRealCoverage\Mutator\MutationTester');
        $tester->expects($this->at('0'))->method('isValid')->will($this->returnValue(true));
        $tester->expects($this->at('1'))->method('isValid')->will($this->returnValue(true));
        $tester->expects($this->at('2'))->method('isValid')->will($this->returnValue(false));

        // act
        $mutator = new Mutator();
        $mutator->testMutations($tester, $generator);

        // assert
        $this->assertFalse($line1->isEnabled());
        $this->assertFalse($line2->isEnabled());
        $this->assertTrue($line3->isEnabled());
    }
}
