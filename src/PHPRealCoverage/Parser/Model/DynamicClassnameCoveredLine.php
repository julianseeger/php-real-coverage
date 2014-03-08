<?php

namespace PHPRealCoverage\Parser\Model;

use PHPRealCoverage\Mutator\MutatableLine;
use PHPRealCoverage\Proxy\Line;

class DynamicClassnameCoveredLine implements Line, MutatableLine
{
    /**
     * @var Line
     */
    private $line;
    /**
     * @var string
     */
    private $className;

    public function __construct(Line $line)
    {
        $this->line = $line;
    }

    public function getFilteredContent()
    {
        return $this->line->getFilteredContent();
    }

    public function isFinal()
    {
        return $this->line->isFinal();
    }

    /**
     * @return bool
     */
    public function isMethod()
    {
        return $this->line->isMethod();
    }

    public function isClass()
    {
        return $this->line->isClass();
    }

    public function getMethodName()
    {
        return $this->line->getMethodName();
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->line->getContent();
    }

    public function __toString()
    {
        return $this->replaceClassname($this->line->__toString());
    }

    public function setNeccessary($neccessary)
    {
        $this->line->setNeccessary($neccessary);
    }

    public function setClassName($className)
    {
        $this->className = $className;
    }

    public function getClassName()
    {
        return $this->className;
    }

    private function replaceClassname($__toString)
    {
        return str_replace($this->line->getClassName(), $this->className, $__toString);
    }

    public function isNeccessary()
    {
        return $this->line->isNeccessary();
    }

    /**
     * @return bool
     */
    public function isCovered()
    {
        return $this->line->isCovered();
    }

    /**
     * @param string $test
     */
    public function addCoverage($test)
    {
        $this->line->addCoverage($test);
    }

    /**
     * @return bool
     */
    public function isExecutable()
    {
        return $this->line->isExecutable();
    }

    public function setExecutable($executable)
    {
        $this->line->setExecutable($executable);
    }

    public function getCoverage()
    {
        return $this->line->getCoverage();
    }

    public function enable()
    {
    }

    public function disable()
    {
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return true;
    }
}
