<?php

namespace PHPRealCoverage\Proxy;

class Proxy
{
    private $proxyName;

    private $classmetadata;

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
        $this->evalClass($class2);
        $proxy::setInstanceClass($this->getCanonicalClassName($class2));
    }

    private function evalClass(ClassMetadata $class)
    {
        $class->setName($class->getName() . '__PROXY__');
        while (class_exists($this->getCanonicalClassName($class))) {
            $class->setName($class->getName() . mt_rand(0, 999));
        }

        eval((string)$class);
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
        foreach ($this->classmetadata->getLines() as $line) {
            if ($line->isMethod()) {
                $builder->addMethod($line->getMethodName()); //TODO remove coupling here
            }
        }
        $builder->loadProxy();
    }
}
