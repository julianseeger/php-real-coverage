<?php

namespace PHPRealCoverage\Proxy;

class Proxy
{
    /**
     * @var string
     */
    private $proxyName;

    /**
     * @var ClassMetadata
     */
    private $classmetadata;

    /**
     * @var string
     */
    private $originalClassName;

    public function __construct(ClassMetadata $classMetadata)
    {
        $this->originalClassName = $classMetadata->getName();
        $this->proxyName = "\\" . $classMetadata->getNamespace() . "\\" . $this->originalClassName;
        $this->classmetadata = $classMetadata;
    }

    public function loadClass(ClassMetadata $class2)
    {
        $proxy = $this->proxyName;
        if (!$this->evalClass($class2)) {
            return false;
        }
        $proxy::setInstanceClass($this->getCanonicalClassName($class2));
        return true;
    }

    private function evalClass(ClassMetadata $class)
    {
        $class->setName($class->getName() . '__PROXY__');
        while (class_exists($this->getCanonicalClassName($class))) {
            $class->setName($class->getName() . mt_rand(0, 999));
        }

        $result = @eval((string)$class) !== false;
        return $result;
    }

    /**
     * @param ClassMetadata $class2
     * @return string
     */
    public function getCanonicalClassName(ClassMetadata $class2)
    {
        return "\\" . trim($class2->getNamespace(), "\\") . "\\" . $class2->getName();
    }

    public function load()
    {
        $this->evalClass($this->classmetadata);

        $builder = new ProxyBuilder();
        $builder->setNamespace($this->classmetadata->getNamespace());
        $builder->setClassName($this->originalClassName);
        $builder->setParentClass("\\" . $this->classmetadata->getNamespace() . "\\" . $this->classmetadata->getName());
        foreach ($this->classmetadata->getMethods() as $method) {
            $builder->addMethod($method);
        }
        $builder->loadProxy();
    }
}
