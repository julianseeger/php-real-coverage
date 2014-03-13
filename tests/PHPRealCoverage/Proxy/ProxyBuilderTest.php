<?php

namespace PHPRealCoverage\Proxy;

class ProxyBuilderTest extends \PHPUnit_Framework_TestCase
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
        $this->assertInstanceOf('\PHPRealCoverage\Proxy\Proxy', $proxy);
        $this->assertTrue($proxy->returnTrue());

        if (!class_exists('PHPRealCoverage\\Proxy\\SomeExchangedClass')) {
            $exchangedCode = file_get_contents(__DIR__ . '/SomeExchangedClass.php');
            $exchangedCode = str_replace('SomeBaseClass', 'SomeBaseClass_original', $exchangedCode);
            $exchangedCode = str_replace('<?php', '', $exchangedCode);
            $this->assertTrue(eval($exchangedCode) !== false);
        }

        SomeBaseClass::setInstanceClass('PHPRealCoverage\\Proxy\\SomeExchangedClass');
        $proxy = new SomeBaseClass();
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

    public function testGetMethodWithInlineContent()
    {
        $builder = new ProxyBuilder();
        $method = "doSomething";

        $this->assertEquals(
            "
            public function doSomething()
            {
                \$this->__PROXYcheckInstance();
                \$reflectionMethod = new \\ReflectionMethod(get_class(\$this->instance), \"doSomething\");
                return \$reflectionMethod->invokeArgs(\$this->instance, func_get_args());
            }
            ",
            $builder->getProxyMethod($method)
        );
    }
}
