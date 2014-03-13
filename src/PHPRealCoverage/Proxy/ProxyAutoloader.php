<?php

namespace PHPRealCoverage\Proxy;

class ProxyAutoloader
{
    /**
     * @var ProxyFactory
     */
    private $factory = null;

    private $autoloaderClosue = null;

    public function __construct(ProxyFactory $factory)
    {
        $this->factory = $factory;
    }

    public function register()
    {
        if (is_null($this->autoloaderClosue)) {
            $factory = $this->factory;
            $this->autoloaderClosue = function ($class) use ($factory) {
                $factory->getProxyForName($class);
            };
        }
        spl_autoload_register($this->autoloaderClosue);
    }

    public function unregister()
    {
        spl_autoload_unregister($this->autoloaderClosue);
    }
}
