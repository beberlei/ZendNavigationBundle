<?php

namespace Bundle\ZendNavigationBundle;

use Symfony\Component\DependencyInjection\Loader\Loader;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Bundle\ZendNavigationBundle\DependencyInjection\ZendNavigationExtension;

class ZendNavigationBundle extends Bundle
{
    /**
     * Customizes the Container instance.
     *
     * @param  Symfony\Component\DependencyInjection\ContainerInterface $container A ContainerInterface instance
     *
     * @return  Symfony\Component\DependencyInjection\BuilderConfiguration A BuilderConfiguration instance
     */
    public function buildContainer(ContainerInterface $container)
    {
        Loader::registerExtension(new ZendNavigationExtension());
    }

    public function getNamespace()
    {
        return __NAMESPACE__;
    }

    public function getPath()
    {
        return strtr(__DIR__, '\\', '/');
    }
}
