<?php

namespace PHPRealCoverage\Mutator;

use PHPRealCoverage\Mutator\Exception\NoMoreMutationsException;
use PHPRealCoverage\Parser\Model\CoveredLine;

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
    { //TODO partial dos / undos are completely missing
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

    public function testMutatorDetectsPermutationsWithWholes()
    {
        //arrange
        $line1 = new DefaultMutatableLine();
        $line2 = new DefaultMutatableLine(); //neccessary
        $line3 = new DefaultMutatableLine();
        $line4 = new DefaultMutatableLine();

        $class = $this->getMockForAbstractClass('PHPRealCoverage\Mutator\MutatableClass');
        $class->expects($this->any())
            ->method('getMutatableLines')
            ->will($this->returnValue(array($line1, $line2, $line3, $line4)));

        $generator = new MutationGenerator($class);

        //tester will only validate if everything is enabled or line1 AND line3 are disabled
        $validatorCallback = function () use ($line1, $line2, $line3, $line4) {
            if ($line1->isEnabled() && $line2->isEnabled() && $line3->isEnabled() && $line4->isEnabled()) {
                return true;
            }
            return !$line1->isEnabled() && $line2->isEnabled() && !$line3->isEnabled() && !$line4->isEnabled();
        };
        $tester = $this->getMockForAbstractClass('PHPRealCoverage\Mutator\MutationTester');
        $tester->expects($this->any())
            ->method('isValid')
            ->will($this->returnCallback($validatorCallback));

        //act
        $mutator = new Mutator();
        $mutator->iterate($tester, $generator);

        //assert
        $this->assertTrue($line1->isEnabled());
        $this->assertTrue($line2->isEnabled());
        $this->assertTrue($line3->isEnabled());
        $this->assertTrue($line4->isEnabled());

        //act
        $mutator->iterate($tester, $generator);

        //assert
        $this->assertTrue($line1->isEnabled());
        $this->assertTrue($line2->isEnabled());
        $this->assertTrue($line3->isEnabled());
        $this->assertTrue($line4->isEnabled());

        //act
        $mutator->iterate($tester, $generator);

        //assert
        $this->assertTrue($line1->isEnabled());
        $this->assertTrue($line2->isEnabled());
        $this->assertTrue($line3->isEnabled());
        $this->assertTrue($line4->isEnabled());

        //act
        $mutator->iterate($tester, $generator);

        //assert
        $this->assertFalse($line1->isEnabled());
        $this->assertTrue($line2->isEnabled());
        $this->assertFalse($line3->isEnabled());
        $this->assertFalse($line4->isEnabled());
    }

    public function testMutatorFindsLinesThatAreOnlyNeccessaryWhenAFollowingOneExists()
    {
        $line0 = new CoveredLine('$c=0;');
        $line0->addCoverage("");
        $line1 = new CoveredLine('$c++;');
        $line1->addCoverage("");
        $this->assertTrue($line0->isEnabled());
        $this->assertTrue($line1->isEnabled());


        $class = $this->mockClass(array($line0, $line1));
        $generator = new MutationGenerator($class);
        //tester will only validate if everything is enabled or line1 AND line3 are disabled
        $validatorCallback = function () use ($line0, $line1) {
            if ($line0->isEnabled()) {
                return true;
            }
            return !$line1->isEnabled(); // line0 enabled or both disabled
        };
        $tester = $this->mockTester($validatorCallback);

        $mutator = new Mutator();
        $mutator->testMutations($tester, $generator);

        $this->assertFalse($line0->isEnabled());
        $this->assertFalse($line1->isEnabled());
    }

    /**
     * @param $validatorCallback
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function mockTester($validatorCallback)
    {
        $tester = $this->getMockForAbstractClass('PHPRealCoverage\Mutator\MutationTester');
        $tester->expects($this->any())
            ->method('isValid')
            ->will($this->returnCallback($validatorCallback));
        return $tester;
    }

    /**
     * @param $lines
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function mockClass($lines)
    {
        $class = $this->getMockForAbstractClass('PHPRealCoverage\Mutator\MutatableClass');
        $class->expects($this->any())
            ->method('getMutatableLines')
            ->will($this->returnValue($lines));
        return $class;
    }
}
