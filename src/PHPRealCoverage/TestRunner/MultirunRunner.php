<?php

namespace PHPRealCoverage\TestRunner;

class MultirunRunner extends \PHPUnit_TextUI_TestRunner
{
    private static $loaderCache = array();

    public function getTest($suiteClassName, $suiteClassFile = '', $suffixes = '')
    {
        $cacheKey = $this->getCacheKey($suiteClassName, $suiteClassFile, $suffixes);
        if (isset(self::$loaderCache[$cacheKey])) {
            return self::$loaderCache[$cacheKey];
        }

        $cacheContent = parent::getTest($suiteClassName, $suiteClassFile, $suffixes);
        self::$loaderCache[$cacheKey] = $cacheContent;
        return $cacheContent;
    }

    private function getCacheKey($suiteClassName, $suiteClassFile, $suffixes)
    {
        $suffixString = is_array($suffixes) ? join(' ', $suffixes) : $suffixes;
        return $suiteClassName . ' ' . $suiteClassFile . ' ' . $suffixString;
    }
}
