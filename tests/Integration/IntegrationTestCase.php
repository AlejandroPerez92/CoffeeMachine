<?php

namespace AlexPerez\CoffeeMachine\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class IntegrationTestCase extends KernelTestCase
{
    /** @var Application */
    protected $application;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $this->application = new Application(self::$kernel);
    }

    protected function tearDown(): void
    {

        parent::tearDown();
    }
}
