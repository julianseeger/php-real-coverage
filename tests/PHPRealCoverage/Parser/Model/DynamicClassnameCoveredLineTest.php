<?php

namespace PHPRealCoverage\Parser\Model;

class DynamicClassnameCoveredLineTest extends \PHPUnit_Framework_TestCase
{
    public function testClassnameIsReplaced()
    {
        $originalLine = new CoveredLine('class ClassA{');
        $originalLine->setMethod(true);
        $originalLine->setClassName('ClassA');
        $line = new DynamicClassnameCoveredLine($originalLine);

        $line->setClassName('ClassB');
        $this->assertEquals('ClassB', $line->getClassName());
        $this->assertEquals('class ClassB{', (string)$line);
    }

    public function testInstanceServesAsProxy()
    {
        $line = new CoveredLine('content');
        $line->setClassName('someclass');
        $line->setMethodName('somemethod');
        $line->setClass(true);
        $line->setMethod(true);
        $line->setNeccessary(false);
        $line->setFinal(true);
        $line->setCovered(true);

        $sut = new DynamicClassnameCoveredLine($line);
        $this->assertEquals('content', $sut->getContent());
        $this->assertEquals('//content', $sut->getFilteredContent());
        $this->assertEquals('somemethod', $sut->getMethodName());
        $this->assertTrue($sut->isClass());
        $this->assertTrue($sut->isMethod());
        $this->assertTrue($sut->isFinal());
        $this->assertFalse($sut->isNeccessary());
        $this->assertFalse($line->isNeccessary());
        $this->assertTrue($line->isCovered());
        $this->assertTrue($sut->isCovered());

        $sut->setNeccessary(true);
        $this->assertTrue($sut->isNeccessary());
        $this->assertTrue($line->isNeccessary());

        $sut->setCovered(false);
        $this->assertFalse($sut->isCovered());
        $this->assertFalse($line->isCovered());
    }
}
