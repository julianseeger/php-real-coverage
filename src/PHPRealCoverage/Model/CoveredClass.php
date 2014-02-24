<?php


namespace PHPRealCoverage\Model;


class CoveredClass
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var CoveredLine[]
     */
    private $lines = array();

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

    public function addLine($lineNumber, CoveredLine $line)
    {
        $this->lines[$lineNumber] = $line;
    }

    public function getLines()
    {
        return $this->lines;
    }
}
