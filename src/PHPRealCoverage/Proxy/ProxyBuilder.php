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
            $proxyContent = $this->getProxyMethod($method, $proxyContent);
        }
        $proxyContent = $this->getProxyFooter($proxyContent);

        eval($proxyContent);
    }

    /**
     * @return string
     */
    private function getProxyHeader()
    {
        $proxyContent = "
        namespace $this->namespace;
        class $this->className extends $this->parentClass {
            private static \$instanceClass;
            private \$instance;

            public function __construct()
            {
                \$parentClassName = \"$this->parentClass\";
                self::\$instanceClass = get_class(new \$parentClassName());
                \$this->createInstance();
            }

            public function createInstance()
            {
                \$instanceClass = self::\$instanceClass;
                \$this->instance = new \$instanceClass();
            }

            public static function setInstanceClass(\$instanceClass)
            {
                self::\$instanceClass = \$instanceClass;
            }

            public function checkInstance()
            {
                if (get_class(\$this->instance) !== self::\$instanceClass) {
                    \$this->instance = new self::\$instanceClass();
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
     * @param $proxyContent
     * @return string
     */
    private function getProxyMethod($method, $proxyContent)
    {
        $proxyContent .= "
            public function $method()
            {
                \$this->checkInstance();
                \$reflectionMethod = new \\ReflectionMethod(get_class(\$this->instance), \"$method\");
                return \$reflectionMethod->invokeArgs(\$this->instance, func_get_args());
            }
            ";
        return $proxyContent;
    }
}
