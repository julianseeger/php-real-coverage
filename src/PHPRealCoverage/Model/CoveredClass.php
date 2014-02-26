<?php


namespace PHPRealCoverage\Model;

use PHPRealCoverage\Proxy\ClassMetadata;

class CoveredClass implements ClassMetadata
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Line[]
     */
    private $lines = array();
    private $namespace;

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    public function addLine($lineNumber, Line $line)
    {
        $this->lines[$lineNumber] = $line;
    }

    public function getLines()
    {
        return $this->lines;
    }

    public function __toString()
    {
        return join("\n", $this->lines);
    }

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }
}
