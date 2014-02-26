<?php

namespace PHPRealCoverage\Proxy;

class Proxy
{
    private $proxyName;

    public function __construct(ClassMetadata $classMetadata)
    {
        $originalClassName = $classMetadata->getName();
        $this->proxyName = "\\" . $classMetadata->getNamespace() . "\\" . $originalClassName;
        $this->evalClass($classMetadata);

        $builder = new ProxyBuilder();
        $builder->setNamespace($classMetadata->getNamespace());
        $builder->setClassName($originalClassName);
        $builder->setParentClass("\\" . $classMetadata->getNamespace() . "\\" . $classMetadata->getName());
        foreach ($classMetadata->getLines() as $line) {
            if ($line->isMethod()) {
                $builder->addMethod($line->getMethodName()); //TODO remove coupling here
            }
        }
        $builder->loadProxy();
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
        return "\\" . $class2->getNamespace() . "\\" . $class2->getName();
    }
}
