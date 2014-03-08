<?php

namespace PHPRealCoverage\Parser;

use PHPRealCoverage\Parser\Exception\ParserException;
use PHPRealCoverage\Parser\Model\CoveredClass;
use PHPRealCoverage\Parser\Model\CoveredLine;
use PHPRealCoverage\Parser\Model\DynamicClassnameCoveredClass;

class ClassParser
{
    const NAMESPACE_PATTERN = '/namespace ([^\s]+);/Usi';
    const CLASSNAME_PATTERN = '/(^|\n)[^*\/\n]*class\s+([^\s{]+?)[\s{\n]*/Usi';
    const CLASSLINE_PATTERN = '/^[^*\/]*class\s+([^\s{]+?)[\s{\n]*/Usi';
    const METHOD_PATTERN = '/\s*(final)?\s*(public?|private?|protected?)\s*(static)?\s*function\s+(\w+?)/Usi';

    /**
     * @param $filename
     * @return CoveredClass
     */
    public function parse($filename)
    {
        $content = file_get_contents($filename);

        $class = $this->parseString($content);
        $class->setFilename($filename);
        return $class;
    }

    /**
     * @param string $content
     */
    public function parseName($content)
    {
        if (!preg_match(self::CLASSNAME_PATTERN, $content, $matches)) {
            throw new ParserException("Failed to parse name of: " . $content);
        }
        return $matches[2];
    }

    /**
     * @param $input
     * @return \PHPRealCoverage\Proxy\Line
     */
    public function parseLine($input)
    {
        $line = new CoveredLine($input);
        $this->detectMethod($input, $line);
        $this->detectClass($input, $line);
        return $line;
    }

    public function detectMethod($input, CoveredLine $line)
    {
        $match = preg_match(self::METHOD_PATTERN, $input, $matches);
        if (!$match) {
            return;
        }

        $line->setMethod(true);
        $line->setFinal($matches[1] === "final");
        $line->setMethodName($matches[4]);
    }

    private function detectClass($input, CoveredLine $line)
    {

        $line->setClass((bool)preg_match(self::CLASSLINE_PATTERN, $input, $matches));

        if ($line->isClass()) {
            $line->setClassName($matches[1]);
        }
    }

    /**
     * @param string $content
     */
    public function parseNamespace($content)
    {
        if (!preg_match(self::NAMESPACE_PATTERN, $content, $matches)) {
            throw new ParserException("Failed to parse Namespace of : \n" . $content);
        }
        return $matches[1];
    }

    /**
     * @param string $content
     * @return DynamicClassnameCoveredClass
     */
    public function parseString($content)
    {
        $class = new DynamicClassnameCoveredClass();
        $class->setName($this->parseName($content));
        $class->setNamespace($this->parseNamespace($content));

        $lines = explode("\n", $content);
        $lineNumber = 1;
        foreach ($lines as $lineAsString) {
            $line = $this->parseLine($lineAsString);
            $class->addLine($lineNumber++, $line);
        }

        return $class;
    }
}
