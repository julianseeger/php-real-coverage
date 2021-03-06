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
        spl_autoload_register($this->getAutoloaderClosure(), true, true);
    }

    public function unregister()
    {
        spl_autoload_unregister($this->autoloaderClosue);
    }

    public function getAutoloaderClosure()
    {
        if (is_null($this->autoloaderClosue)) {
            $factory = $this->factory;
            $this->autoloaderClosue = function ($class) use ($factory) {
                if (!$factory->supports($class)) {
                    return;
                }
                $factory->getProxyForName($class);
            };
        }
        return $this->autoloaderClosue;
    }
}
