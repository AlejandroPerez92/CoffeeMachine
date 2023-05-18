<?php

namespace AlexPerez\CoffeeMachine\Tests\Integration\Console;

use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\ProductSale;
use AlexPerez\CoffeeMachine\Tests\Integration\IntegrationTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class GetSalesCommandTest extends IntegrationTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        file_put_contents($_ENV['DATA_FILE'],$this->fixtures());
    }

    public function testCoffeeMachineReturnsTheExpectedOutput(): void
    {
        $command = $this->application->find('app:sales');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        self::assertEquals("chocolate => 360\nsugar => 0\nstick => 0\ntea => 120\ncoffee => 150\n", $output);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        if (file_exists($_ENV['DATA_FILE'])) {
            unlink($_ENV['DATA_FILE']);
        }
    }

    private function fixtures():string
    {
        $data = [
            new ProductSale('chocolate',360),
            new ProductSale('sugar',0),
            new ProductSale('stick',0),
            new ProductSale('tea',120),
            new ProductSale('coffee',150)
        ];
        return serialize($data);
    }
}
