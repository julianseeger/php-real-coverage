<?php

namespace PHPRealCoverage\Proxy;

class ProxyTest extends \PHPUnit_Framework_TestCase
{
    public function testProxyCallsDifferentClasses()
    {
        $this->loadBaseClass('SomeBaseClass');

        $proxyBuilder = new ProxyBuilder();
        $proxyBuilder->setNamespace('PHPRealCoverage\Proxy');
        $proxyBuilder->setClassName('SomeBaseClass');
        $proxyBuilder->setParentClass('\PHPRealCoverage\Proxy\SomeBaseClass_original');
        $proxyBuilder->addMethod('returnTrue');
        $proxyBuilder->loadProxy();

        $proxy = new SomeBaseClass();
        $this->assertInstanceOf('\PHPRealCoverage\Proxy\SomeBaseClass', $proxy);
        $this->assertTrue($proxy->returnTrue());

        if (!class_exists('PHPRealCoverage\\Proxy\\SomeExchangedClass')) {
            include __DIR__ . '/SomeExchangedClass.php';
        }

        SomeBaseClass::setInstanceClass('PHPRealCoverage\\Proxy\\SomeExchangedClass');
        $this->assertFalse($proxy->returnTrue());
    }

    private function loadBaseClass($className)
    {
        $content = file_get_contents(__DIR__ . '/' . $className . '.php');
        $content = str_replace($className, $className . '_original', $content);
        $content = str_replace('<?php', '', $content);
        eval($content);
    }

    public function testProxyPassesConstructorArguments()
    {
        $this->loadBaseClass('SomeClassWithConstructorArguments');

        $builder = new ProxyBuilder();
        $builder->setNamespace('PHPRealCoverage\Proxy');
        $builder->setClassName('SomeClassWithConstructorArguments');
        $builder->setParentClass('\PHPRealCoverage\Proxy\SomeClassWithConstructorArguments_original');
        $builder->addMethod('getSomeParameter');
        $builder->loadProxy();

        $instance = new SomeClassWithConstructorArguments('passedByConstructor');
        $this->assertEquals('passedByConstructor', $instance->getSomeParameter());
    }
}
