<?php

namespace Deliverea\CoffeeMachine\Tests\Integration;

use Deliverea\CoffeeMachine\App\AppKernel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Dotenv\Dotenv;

class IntegrationTestCase extends TestCase
{
    /** @var Application */
    protected $application;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = new AppKernel('test',false);
        $kernel->boot();

        $dotenv = new Dotenv();
        $dotenv->load(__DIR__.'/../../.env'.'.test');

        $container = $kernel->getContainer();
        $this->application = $container->get(Application::class);
    }

    protected function tearDown(): void
    {

        parent::tearDown();
    }
}
