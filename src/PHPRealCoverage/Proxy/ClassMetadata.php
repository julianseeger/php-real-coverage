<?php

namespace PHPRealCoverage\Proxy;

interface ClassMetadata
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getNamespace();

    /**
     * @return string[]
     */
    public function getMethods();

    /**
     * @return Line[]
     */
    public function getLines();

    /**
     * @param int $lineNumber
     * @return Line
     */
    public function getLine($lineNumber);

    /**
     * @return string
     */
    public function __toString();
}
