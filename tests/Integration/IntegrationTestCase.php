<?php

namespace AlexPerez\CoffeeMachine\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IntegrationTestCase extends WebTestCase
{
    protected Application $application;
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->application = new Application(self::$kernel);
        $redis = $this->getContainer()->get('alex-perez.shared.redis_client');
        $redis->flushAll();
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
