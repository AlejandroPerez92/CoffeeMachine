<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Shared\Infrastructure\Command;

use AlexPerez\CoffeeMachine\Sales\ProductSales\Infrastructure\Ui\ConsoleGetAllProductSalesObject;
use League\Tactician\CommandBus;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('app:sales')]
final class GetSalesCommand extends Command
{
    public function __construct(private CommandBus $queryBus)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var ConsoleGetAllProductSalesObject $sales */
        $sales = $this->queryBus->handle(new ConsoleGetAllProductSalesObject());
        $output->write($sales->getString());

        return Command::SUCCESS;
    }
}