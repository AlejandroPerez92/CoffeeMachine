<?php

namespace Deliverea\CoffeeMachine\App\Command;

use Deliverea\CoffeeMachine\Order\Application\Command\CreateOrderCommand;
use Deliverea\CoffeeMachine\Order\Application\Command\CreateOrderLineCommand;
use Deliverea\CoffeeMachine\Order\Application\Command\PayOrderCommand;
use Deliverea\CoffeeMachine\Order\Domain\Exception\LimitUnitsException;
use Deliverea\CoffeeMachine\Order\Domain\Exception\NotEnoughAmountToPayOrder;
use Deliverea\CoffeeMachine\Order\Domain\Exception\NotFoundProductException;
use Deliverea\CoffeeMachine\Order\Domain\OrderId;
use Deliverea\CoffeeMachine\Shared\Domain\PositiveInteger\NegativeValueException;
use League\Tactician\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeDrinkCommand extends Command
{
    protected static $defaultName = 'app:order-drink';
    private CommandBus $commandBus;
    private CommandBus $queryBus;

    public function __construct(CommandBus $commandBus, CommandBus $queryBus)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    protected function configure()
    {
        $this->addArgument(
            'drink-type',
            InputArgument::REQUIRED,
            'The type of the drink. (Tea, Coffee or Chocolate)'
        );

        $this->addArgument(
            'money',
            InputArgument::REQUIRED,
            'The amount of money given by the user'
        );

        $this->addArgument(
            'sugars',
            InputArgument::OPTIONAL,
            'The number of sugars you want. (0, 1, 2)',
            0
        );

        $this->addOption(
            'extra-hot',
            'e',
            InputOption::VALUE_NONE,
            $description = 'If the user wants to make the drink extra hot'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $drinkType = strtolower($input->getArgument('drink-type'));
        $money = (float) $input->getArgument('money');
        $sugars = (int) $input->getArgument('sugars');
        $extraHot = $input->getOption('extra-hot');

        $orderId = OrderId::Create();

        $this->commandBus->handle(new CreateOrderCommand($orderId, $extraHot));

        try {
            $this->commandBus->handle(new CreateOrderLineCommand($drinkType, 1, $orderId->value()));
            $this->commandBus->handle(new CreateOrderLineCommand('sugar', $sugars, $orderId->value()));
        } catch (NotFoundProductException $e) {
            $output->writeln('The drink type should be tea, coffee or chocolate.');

            return Command::FAILURE;
        } catch (LimitUnitsException | NegativeValueException $e) {
            $output->writeln('The number of sugars should be between 0 and 2.');

            return Command::FAILURE;
        }

        try {
            $amount = (int) round(($money * 100), 0);
            $this->commandBus->handle(new PayOrderCommand($orderId, $amount));
        } catch (NotEnoughAmountToPayOrder $e) {
            $output->writeln(sprintf('The %s costs %s.', $drinkType, $e->cost()));

            return Command::FAILURE;
        }

        $output->write('You have ordered a ' . $drinkType);

        if ($extraHot) {
            $output->write(' extra hot');
        }

        if ($sugars > 0) {
            $output->write(' with ' . $sugars . ' sugars (stick included)');
        }

        $output->writeln('');

        return Command::SUCCESS;
    }
}
