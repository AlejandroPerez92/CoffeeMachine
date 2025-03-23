<?php

namespace AlexPerez\CoffeeMachine\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class IntegrationTestCase extends KernelTestCase
{
    protected Application $application;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $this->application = new Application(self::$kernel);
        $redis = $this->getContainer()->get('alex-perez.shared.redis_client');
        $redis->flushAll();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
