<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Deliverea\CoffeeMachine\App\AppKernel;
use Symfony\Component\Console\Application;

$kernel = new AppKernel('dev',false);
$kernel->boot();

$container = $kernel->getContainer();
$application = $container->get(Application::class);
$application->run();
