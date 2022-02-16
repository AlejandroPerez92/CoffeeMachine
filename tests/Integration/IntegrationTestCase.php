<?php

namespace Deliverea\CoffeeMachine\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;

class IntegrationTestCase extends TestCase
{
    /** @var Application */
    protected $application;

    protected function setUp(): void
    {
        parent::setUp();

        $this->application = new Application();
    }

    protected function tearDown(): void
    {

        parent::tearDown();
    }
}
