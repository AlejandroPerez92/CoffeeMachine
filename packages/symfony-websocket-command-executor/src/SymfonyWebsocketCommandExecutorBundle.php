<?php

namespace AlexPerez\SymfonyWebsocketCommandExecutor;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;

class SymfonyWebsocketCommandExecutorBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/Resources/config')
        );
        $loader->load('services.xml');
    }
}