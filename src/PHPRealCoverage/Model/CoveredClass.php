<?php


namespace PHPRealCoverage\Model;


class CoveredClass
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
        $strings = array_map(
            function ($line) {
                return (string)$line;
            },
            $this->lines
        );
        return join("\n", $strings);
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
