<?php

namespace PHPRealCoverage\Model;

class DynamicClassnameCoveredLine implements Line
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
}
