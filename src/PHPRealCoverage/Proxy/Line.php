<?php
namespace PHPRealCoverage\Proxy;

interface Line
{
    public function getFilteredContent();

    public function isFinal();

    /**
     * @return bool
     */
    public function isMethod();

    public function isClass();

    public function getMethodName();

    public function getClassName();

    /**
     * @return string
     */
    public function getContent();

    public function __toString();

    public function setNeccessary($neccessary);

    public function isNeccessary();
}
