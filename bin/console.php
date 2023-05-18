<?php
require_once __DIR__ . '/../vendor/autoload.php';

use AlexPerez\CoffeeMachine\App\AppKernel;
use Symfony\Component\Console\Application;
use Symfony\Component\Dotenv\Dotenv;

$kernel = new AppKernel('dev',false);
$kernel->boot();

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/../.env'.'.dev');

$container = $kernel->getContainer();
$application = $container->get(Application::class);
$application->run();
