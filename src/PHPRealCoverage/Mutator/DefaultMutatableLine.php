<?php

namespace PHPRealCoverage\Mutator;

class DefaultMutatableLine implements MutatableLine
{
    /**
     * @var bool
     */
    private $enabled = true;

    public function enable()
    {
        $this->enabled = true;
    }

    public function disable()
    {
        $this->enabled = false;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }
}
