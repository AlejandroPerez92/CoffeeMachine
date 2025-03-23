<?php

namespace AlexPerez\CoffeeMachine\Tests\Integration\Console;

use AlexPerez\CoffeeMachine\Tests\Integration\IntegrationTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Console\Tester\CommandTester;

class MakeDrinkCommandTest extends IntegrationTestCase
{

    #[DataProvider('ordersProvider')]
    public function testCoffeeMachineReturnsTheExpectedOutput(
        string $drinkType,
        string $money,
        int $sugars,
        int $extraHot,
        string $expectedOutput
    ): void {
        $command = $this->application->find('app:order-drink');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'     => $command->getName(),

            // pass arguments to the helper
            'drink-type'  => $drinkType,
            'money'       => $money,
            'sugars'      => $sugars,
            '--extra-hot' => $extraHot
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertSame($expectedOutput, $output);
    }

    public static function ordersProvider(): array
    {
        return [
            [
                'chocolate',
                '0.7',
                1,
                0,
                'You have ordered a chocolate with 1 sugars (stick included)' . PHP_EOL
            ],
            [
                'tea',
                '0.4',
                0,
                1,
                'You have ordered a tea extra hot' . PHP_EOL
            ],
            [
                'coffee',
                '2',
                2,
                1,
                'You have ordered a coffee extra hot with 2 sugars (stick included)' . PHP_EOL
            ],
            [
                'coffee',
                '0.2',
                2,
                1,
                'The coffee costs 0.5.' . PHP_EOL
            ],
            [
                'chocolate',
                '0.3',
                2,
                1,
                'The chocolate costs 0.6.' . PHP_EOL
            ],
            [
                'tea',
                '0.1',
                2,
                1,
                'The tea costs 0.4.' . PHP_EOL
            ],
            [
                'tea',
                '0.5',
                -1,
                1,
                'The number of sugars should be between 0 and 2.' . PHP_EOL
            ],
            [
                'tea',
                '0.5',
                3,
                1,
                'The number of sugars should be between 0 and 2.' . PHP_EOL
            ],
            [
                'caca',
                '0.5',
                1,
                1,
                'The drink type should be tea, coffee or chocolate.' . PHP_EOL
            ]
        ];
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        if (file_exists($_ENV['DATA_FILE'])) {
            unlink($_ENV['DATA_FILE']);
        }
    }
}
