<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\App\Command;

use AlexPerez\CoffeeMachine\Sales\Infrastructure\Ui\ConsoleGetAllProductSalesObject;
use League\Tactician\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class GetSalesCommand extends Command
{
    protected static $defaultName = 'app:sales';
    private CommandBus $queryBus;

    public function __construct(CommandBus $queryBus)
    {
        parent::__construct();
        $this->queryBus = $queryBus;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var ConsoleGetAllProductSalesObject $sales */
        $sales = $this->queryBus->handle(new ConsoleGetAllProductSalesObject());
        $output->write($sales->getString());

        return Command::SUCCESS;
    }
}