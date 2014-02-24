<?php


namespace PHPRealCoverage\Model;


class CoveredLine
{
    private $content;

    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }
}
