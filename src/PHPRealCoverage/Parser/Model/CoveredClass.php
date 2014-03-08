<?php


namespace PHPRealCoverage\Parser\Model;

use PHPRealCoverage\Proxy\ClassMetadata;
use PHPRealCoverage\Proxy\Line;

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

    private $filename;

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param integer $lineNumber
     */
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

    /**
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @return string[]
     */
    public function getMethods()
    {
        $methods = array();
        foreach ($this->lines as $line) {
            if ($line->isMethod()) {
                $methods[] = $line->getMethodName();
            }
        }
        return $methods;
    }

    /**
     * @param int $lineNumber
     * @return Line
     */
    public function getLine($lineNumber)
    {
        return $this->lines[$lineNumber];
    }

    /**
     * @param mixed $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }
}
