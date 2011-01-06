<?php

namespace Bundle\ZendNavigationBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Resource\FileResource;

class ZendNavigationExtension extends Extension
{
    public function navLoad($config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, __DIR__.'/../Resources/config');
        $loader->load('navigation.xml');

        $configDir = $container->getParameter('kernel.root_dir').'/config/';
        foreach ($config AS $containerName => $yamlConfigFile) {
            if (!is_string($yamlConfigFile)) {
                $yamlConfigFile = $containerName . '.yml';
            }
            $yamlConfigFile = "nav_".$yamlConfigFile;

            $yamlConfigFile = $configDir . $yamlConfigFile;

            $def = new Definition("Bundle\ZendNavigationBundle\Navigation");
            $def->setFactoryMethod('factory');
            $def->addArgument(new Reference('router'));
            $def->addArgument(new Reference('request'));
            $def->addArgument($yamlConfigFile);

            $container->setDefinition('zend.navigation.'.$containerName, $def);
        } 
    }

    public function getXsdValidationBasePath()
    {
        return false;
    }

    public function getNamespace()
    {
        return 'http://www.whitewashing.de/schema/zend-navigation';
    }

    public function getAlias()
    {
        return "navigation";
    }
}
