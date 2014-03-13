<?php

namespace PHPRealCoverage\Proxy;

class ProxyFactory
{
    private $classes;
    private $proxies = array();

    /**
     * @param ClassMetadata[] $classes
     */
    public function __construct(array $classes)
    {
        $this->createClassHashMap($classes);
    }

    public function supports($classname)
    {
        return isset($this->classes[$classname]);
    }

    /**
     * @param ClassMetadata[] $classes
     */
    private function createClassHashMap(array $classes)
    {
        $this->classes = array();
        foreach ($classes as $class) {
            $this->classes[$this->getFullQualifiedClassName($class)] = $class;
        }
    }

    /**
     * @param ClassMetadata $class
     * @return string
     */
    private function getFullQualifiedClassName(ClassMetadata $class)
    {
        return '\\' . $class->getNamespace() . '\\' . $class->getName();
    }

    public function getProxyForName($classname)
    {
        if (!$this->supports($classname)) {
            throw new UnsupportedClassException("Class " . $classname . " not found");
        }

        if (!isset($this->proxies[$classname])) {
            $proxy = new Proxy($this->classes[$classname]);
            $proxy->load();
            $this->proxies[$classname] = $proxy;
        }

        return $this->proxies[$classname];
    }

    /**
     * @param ClassMetadata $class
     * @return Proxy
     */
    public function getProxy(ClassMetadata $class)
    {
        return $this->getProxyForName($this->getFullQualifiedClassName($class));
    }
}
