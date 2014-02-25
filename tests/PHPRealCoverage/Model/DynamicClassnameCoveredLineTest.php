<?php

namespace PHPRealCoverage\Model;

class DynamicClassnameCoveredLineTest extends \PHPUnit_Framework_TestCase
{
    public function testClassnameIsReplaced()
    {
        $originalLine = new CoveredLine('class ClassA{');
        $originalLine->setMethod(true);
        $originalLine->setClassName('ClassA');
        $line = new DynamicClassnameCoveredLine($originalLine);

        $line->setClassName('ClassB');
        $this->assertEquals('class ClassB{', (string)$line);
    }
}
