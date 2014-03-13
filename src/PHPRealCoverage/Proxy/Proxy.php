<?php

namespace PHPRealCoverage\Proxy;

interface Proxy
{
    public function __construct();

    /**
     * Creates an instance of the original class by reflections
     * @param $constructorArguments
     * @return mixed
     */
    public function __PROXYcreateInstance($constructorArguments);

    /**
     * Set class to redirect methodcalls to
     * @param $instanceClass
     * @return mixed
     */
    public static function setInstanceClass($instanceClass);

    /**
     * Make sure, that the current proxies instances original class
     * is of the same type as the original class of the ...whatever...
     * @return mixed
     */
    public function __PROXYcheckInstance();
}
