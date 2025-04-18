<?php

namespace AlexPerez\CoffeeMachine\Shared\Infrastructure\Command;

use AlexPerez\CoffeeMachine\Order\Order\Domain\OrderRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('app:list-orders')]
class ListOrdersCommand extends Command
{
    public function __construct(private OrderRepositoryInterface $orderRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('List all orders');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $orders = $this->orderRepository->findAll();
        
        if (empty($orders)) {
            $output->writeln('No orders found.');
            return Command::SUCCESS;
        }
        
        $table = new Table($output);
        $table->setHeaders(['Order ID', 'Total', 'Created', 'Paid', 'Hot', 'Products']);
        
        foreach ($orders as $order) {
            $products = [];
            foreach ($order->lines() as $line) {
                if ($line->productName !== 'sugar') {
                    $products[] = $line->productName . ' (x' . $line->units->value() . ')';
                }
            }
            
            $table->addRow([
                $order->id()->value(),
                $order->total()->value() / 100 . '€',
                $order->created()->format('Y-m-d H:i:s'),
                $order->paid() ? 'Yes' : 'No',
                $order->hot() ? 'Yes' : 'No',
                implode(', ', $products)
            ]);
        }
        
        $table->render();
        return Command::SUCCESS;
    }
}