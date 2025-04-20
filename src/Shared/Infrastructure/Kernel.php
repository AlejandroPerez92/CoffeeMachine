<?php

namespace AlexPerez\CoffeeMachine\Shared\Infrastructure;

use AlexPerez\CoffeeMachine\Shared\Infrastructure\DependencyInjection\CompilerPass\CompilerPass\SubscribersCompilerPass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new SubscribersCompilerPass());
    }
}
