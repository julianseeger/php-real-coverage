<?php


namespace PHPRealCoverage\Model;


class CoveredLine
{
    private $content;
    private $method = false;
    private $final = false;
    private $methodName;
    private $neccessary = true;

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
        $comment = $this->neccessary ? '' : '//';
        return $comment . $this->content;
    }

    public function setNeccessary($neccessary)
    {
        $this->neccessary = $neccessary;
    }
}
