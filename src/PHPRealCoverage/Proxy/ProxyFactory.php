<?php

namespace PHPRealCoverage\Proxy;

class ProxyFactory
{
    private $classes;

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

    public function getProxy($classname)
    {
        if (!$this->supports($classname)) {
            throw new UnsupportedClassException();
        }

        $proxy = new Proxy($this->classes[$classname]);
        $proxy->load();
        return $proxy;
    }
}
