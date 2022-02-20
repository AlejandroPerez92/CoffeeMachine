<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\App;

use Deliverea\CoffeeMachine\App\DependencyInjection\CompilerPass\CommandsToApplicationCompilerPass;
use Deliverea\CoffeeMachine\App\DependencyInjection\CompilerPass\SubscribersCompilerPass;
use League\Tactician\Bundle\TacticianBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\EventDispatcher\DependencyInjection\AddEventAliasesPass;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;
use Symfony\Component\HttpKernel\Kernel;

final class AppKernel extends Kernel
{

    /**
     * In more complex app, add bundles here
     */
    public function registerBundles(): array
    {
        return [
            new TacticianBundle()
        ];
    }

    /**
     * Load all services
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/config/services.yaml');
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new CommandsToApplicationCompilerPass());
        $container->addCompilerPass(new RegisterListenersPass());
        $container->addCompilerPass(new SubscribersCompilerPass());
    }
}