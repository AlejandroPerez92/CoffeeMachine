<?php

namespace Deliverea\CoffeeMachine\Tests\Integration;

use Deliverea\CoffeeMachine\App\AppKernel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;

class IntegrationTestCase extends TestCase
{
    /** @var Application */
    protected $application;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = new AppKernel('dev',false);
        $kernel->boot();

        $container = $kernel->getContainer();
        $this->application = $container->get(Application::class);
    }

    protected function tearDown(): void
    {

        parent::tearDown();
    }
}
