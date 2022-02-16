#!/usr/bin/env php
<?php
// application.php

require __DIR__ . '/../vendor/autoload.php';

use Deliverea\CoffeeMachine\App\Command\MakeDrinkCommand;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

$containerBuilder = new ContainerBuilder();
$loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__ . '/./config'));
$loader->load('services.yaml');
$containerBuilder->compile();

$application = new Application();

$application->add(new MakeDrinkCommand());

$application->run();
