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

    public function getProxy()
    {
        $proxyContent = $this->getProxyHeader();
        foreach ($this->methods as $method) {
            $proxyContent = $this->getProxyMethod($method, $proxyContent);
        }
        $proxyContent = $this->getProxyFooter($proxyContent);

        eval($proxyContent);
        $proxyClass = $this->namespace . "\\" . $this->className;
        return new $proxyClass();
    }

    /**
     * @return string
     */
    private function getProxyHeader()
    {
        $proxyContent = "
        namespace $this->namespace;
        class $this->className extends $this->parentClass {
            private \$instance;

            public function __construct()
            {
                \$instanceClass = \"$this->parentClass\";
                \$this->instance = new \$instanceClass();
            }

            public function setInstance(\$instance)
            {
                \$this->instance = \$instance;
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
            public function $method() {
                \$reflectionMethod = new \\ReflectionMethod(get_class(\$this->instance), \"$method\");
                return \$reflectionMethod->invokeArgs(\$this->instance, func_get_args());
            }
            ";
        return $proxyContent;
    }
}
