<?php

namespace Deliverea\CoffeeMachine\Tests\Integration\Console;

use Deliverea\CoffeeMachine\Tests\Integration\IntegrationTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class GetSalesCommandTest extends IntegrationTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testCoffeeMachineReturnsTheExpectedOutput(
    ): void {
        $command = $this->application->find('app:sales');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName(),
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        self::assertEquals("chocolate => 360\nsugar => 0\nstick => 0\ntea => 120\ncoffee => 150\n", $output);
    }
}
