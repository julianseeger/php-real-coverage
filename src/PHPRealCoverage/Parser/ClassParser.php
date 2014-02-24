<?php

namespace PHPRealCoverage\Parser;

use PHPRealCoverage\Model\CoveredClass;
use PHPRealCoverage\Model\CoveredLine;

class ClassParser
{
    const CLASSNAME_PATTERN = '/(^|\n)[^*\/\n]*class\s+([^\s]+)[\s{\n]+/Usi';

    /**
     * @var string
     */
    private $filename;

    /**
     * @param string $filename
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return CoveredClass
     */
    public function parse()
    {
        $content = file_get_contents($this->filename);

        $class = new CoveredClass();
        $class->setName($this->parseName($content));
        return $class;
    }

    public function parseName($content)
    {
        preg_match(self::CLASSNAME_PATTERN, $content, $matches);
        return $matches[2];
    }

    /**
     * @param $input
     * @return CoveredLine
     */
    public function parseLine($input)
    {
        $line = new CoveredLine($input);
        return $line;
    }
}
