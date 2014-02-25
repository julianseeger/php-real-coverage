<?php

namespace PHPRealCoverage\Proxy;

class ProxyTest extends \PHPUnit_Framework_TestCase
{
    public function testProxyCallsDifferentClasses()
    {
        $this->loadBaseClass();

        $namespace = 'PHPRealCoverage\Proxy';
        $className = 'SomeBaseClass';
        $parentClass = '\PHPRealCoverage\Proxy\SomeBaseClass_original';

        $proxyBuilder = new ProxyBuilder();
        $proxyBuilder->setNamespace($namespace);
        $proxyBuilder->setClassName($className);
        $proxyBuilder->setParentClass($parentClass);
        $proxyBuilder->addMethod('returnTrue');
        /** @var SomeBaseClass $proxy */
        $proxyBuilder->loadProxy();

        $proxy = new SomeBaseClass();
        $this->assertInstanceOf('\PHPRealCoverage\Proxy\SomeBaseClass', $proxy);
        $this->assertTrue($proxy->returnTrue());

        include __DIR__ . '/SomeExchangedClass.php';

        $someExchangedClass = new SomeExchangedClass();
        SomeBaseClass::setInstanceClass(get_class($someExchangedClass));
        $this->assertFalse($someExchangedClass->returnTrue());
        $this->assertFalse($proxy->returnTrue());
    }

    private function loadBaseClass()
    {
        $content = file_get_contents(__DIR__ . '/SomeBaseClass.php');
        $content = str_replace('SomeBaseClass', 'SomeBaseClass_original', $content);
        $content = str_replace('<?php', '', $content);
        eval($content);
    }
}
