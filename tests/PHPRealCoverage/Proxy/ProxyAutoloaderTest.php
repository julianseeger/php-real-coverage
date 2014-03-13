<?php

namespace PHPRealCoverage\Proxy;

class ProxyAutoloaderTest extends \PHPUnit_Framework_TestCase
{
    private $autoloader;

    public function testRegisterAddsAnSplAutoloader()
    {
        $autoloaderCount = count(spl_autoload_functions());

        $this->autoloader = new ProxyAutoloader(new ProxyFactory(array()));
        $this->autoloader->register();

        $this->assertEquals($autoloaderCount + 1, count(spl_autoload_functions()));
        $this->assertEquals($this->autoloader->getAutoloaderClosure(), spl_autoload_functions()[0]);
    }

    public function testUnregisterRemovesAnSplAutoloader()
    {
        $autoloaderCount = count(spl_autoload_functions());

        $this->autoloader = new ProxyAutoloader(new ProxyFactory(array()));
        $this->autoloader->register();
        $this->autoloader->unregister();

        $this->assertEquals($autoloaderCount, count(spl_autoload_functions()));
    }

    public function testAutoloaderCallsFactory()
    {
        $factory = $this->getMockBuilder('\PHPRealCoverage\Proxy\ProxyFactory')
            ->disableOriginalConstructor()
            ->getMock();
        $factory->expects($this->once())
            ->method('getProxyForName')
            ->with('Namespaced\\Class');

        /** @var ProxyFactory $factory */
        $this->autoloader = new ProxyAutoloader($factory);
        $this->autoloader->register();
        class_exists('\\Namespaced\\Class');
    }

    protected function tearDown()
    {
        if ($this->autoloader) {
            $this->autoloader->unregister();
        }
    }
}
