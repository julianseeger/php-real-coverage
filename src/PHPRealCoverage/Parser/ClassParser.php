<?php

namespace PHPRealCoverage\Parser;

use PHPRealCoverage\Model\CoveredClass;
use PHPRealCoverage\Model\CoveredLine;

class ClassParser
{
    const CLASSNAME_PATTERN = '/(^|\n)[^*\/\n]*class\s+([^\s]+)[\s{\n]+/Usi';
    const METHOD_PATTERN = '/\s*(public?|private?|protected?)\s*(static)?\s*(final)?\s*function\s+(\w+?)/Usi';

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
        $this->detectMethod($input, $line);
        return $line;
    }

    private function detectMethod($input, CoveredLine $line)
    {
        $match = preg_match(self::METHOD_PATTERN, $input, $matches);
        if (!$match) {
            return;
        }

        $line->setMethod(true);
        $line->setFinal($matches[3] === "final");
        $line->setMethodName($matches[4]);
    }
}
