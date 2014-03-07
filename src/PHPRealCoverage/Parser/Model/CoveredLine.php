<?php


namespace PHPRealCoverage\Parser\Model;


use PHPRealCoverage\Mutator\MutatableLine;
use PHPRealCoverage\Proxy\Line;

class CoveredLine implements Line, MutatableLine
{
    private $content;
    private $method = false;
    private $final = false;
    private $methodName;
    private $neccessary = true;
    private $coveringTest = false;
    private $class = false;
    private $className;
    private $executable = false;

    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return bool
     */
    public function isMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function isFinal()
    {
        return $this->final;
    }

    public function setFinal($final)
    {
        $this->final = $final;
    }

    public function getMethodName()
    {
        return $this->methodName;
    }

    public function setMethodName($methodName)
    {
        $this->methodName = $methodName;
    }

    public function __toString()
    {
        return $this->getFilteredContent();
    }

    public function setNeccessary($neccessary)
    {
        $this->neccessary = $neccessary;
    }

    public function isNeccessary()
    {
        return $this->neccessary;
    }

    public function getFilteredContent()
    {
        $content = $this->getContent();
        if (!$this->neccessary) {
            $content = '//' . $content;
        }

        if (!$this->isMethod()) {
            return $content;
        }
        return str_replace('final ', '', $content);
    }

    public function isClass()
    {
        return $this->class;
    }

    public function setClass($isClass)
    {
        $this->class = $isClass;
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function setClassName($className)
    {
        $this->className = $className;
    }

    /**
     * @param string $test
     */
    public function addCoverage($test)
    {
        $this->coveringTest[] = $test;
    }

    /**
     * @return bool
     */
    public function isCovered()
    {
        return !empty($this->coveringTest);
    }

    public function enable()
    {
        $this->setNeccessary(true);
    }

    public function disable()
    {
        $this->setNeccessary(false);
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->isNeccessary() && $this->isCovered();
    }

    /**
     * @param bool $executable
     */
    public function setExecutable($executable)
    {
        $this->executable = $executable;
    }

    /**
     * @return bool
     */
    public function isExecutable()
    {
        return $this->executable;
    }
}
