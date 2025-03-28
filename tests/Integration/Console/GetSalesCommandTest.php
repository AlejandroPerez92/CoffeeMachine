<?php

namespace AlexPerez\CoffeeMachine\Tests\Integration\Console;

use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\ProductSales;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Infrastructure\RedisProductSalesRepository;
use AlexPerez\CoffeeMachine\Tests\Integration\IntegrationTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class GetSalesCommandTest extends IntegrationTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        /** @var RedisProductSalesRepository $repository */
        $repository = $this->getContainer()->get(RedisProductSalesRepository::class);

        foreach ($this->fixtures() as $fixture) {
            $repository->save($fixture);
        }
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

    private function fixtures(): array
    {
        return [
            new ProductSales('chocolate', 360),
            new ProductSales('sugar', 0),
            new ProductSales('stick', 0),
            new ProductSales('tea', 120),
            new ProductSales('coffee', 150)
        ];
    }
}
