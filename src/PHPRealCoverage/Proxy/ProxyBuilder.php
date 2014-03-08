<?php

namespace PHPRealCoverage\Proxy;

class ProxyBuilder
{
    private $namespace;
    private $className;
    private $parentClass;
    private $methods = array();

    /**
     * @param mixed $className
     */
    public function setClassName($className)
    {
        $this->className = $className;
    }

    /**
     * @param mixed $namspace
     */
    public function setNamespace($namspace)
    {
        $this->namespace = $namspace;
    }

    /**
     * @param mixed $parentClass
     */
    public function setParentClass($parentClass)
    {
        $this->parentClass = $parentClass;
    }

    public function addMethod($method)
    {
        $this->methods[] = $method;
    }

    public function loadProxy()
    {
        $proxyContent = $this->getProxyHeader();
        foreach ($this->methods as $method) {
            $proxyContent .= $this->getProxyMethod($method);
        }
        $proxyContent = $this->getProxyFooter($proxyContent);

        $result = @eval($proxyContent) !== false;
    }

    /**
     * @return string
     */
    private function getProxyHeader()
    {
        $proxyContent = "
        namespace $this->namespace;
        class $this->className extends $this->parentClass {
            private static \$instanceClass = '$this->parentClass';
            private \$instance;
            private \$constructorArguments;

            public function __construct()
            {
                \$this->constructorArguments = func_get_args();
                \$this->__PROXYcreateInstance(\$this->constructorArguments);
            }

            public function __PROXYcreateInstance(\$constructorArguments)
            {
                \$reflectionClass = new \\ReflectionClass(self::\$instanceClass);
                \$this->instance = \$reflectionClass->newInstanceArgs(\$constructorArguments);
            }

            public static function setInstanceClass(\$instanceClass)
            {
                self::\$instanceClass = \$instanceClass;
            }

            public function __PROXYcheckInstance()
            {
                if (get_class(\$this->instance) !== self::\$instanceClass) {
                    \$this->__PROXYcreateInstance(\$this->constructorArguments);
                }
            }
        ";
        return $proxyContent;
    }

    /**
     * @param $proxyContent
     * @return string
     */
    private function getProxyFooter($proxyContent)
    {
        $proxyContent .= "}";
        return $proxyContent;
    }

    /**
     * @param $method
     * @internal param $proxyContent
     * @return string
     */
    public function getProxyMethod($method)
    {
        return "
            public function $method()
            {
                \$this->__PROXYcheckInstance();
                \$reflectionMethod = new \\ReflectionMethod(get_class(\$this->instance), \"$method\");
                return \$reflectionMethod->invokeArgs(\$this->instance, func_get_args());
            }
            ";
    }
}
