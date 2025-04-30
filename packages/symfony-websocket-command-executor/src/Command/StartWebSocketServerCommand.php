<?php

namespace AlexPerez\SymfonyWebsocketCommandExecutor\Command;

use AlexPerez\SymfonyWebsocketCommandExecutor\Server\WebSocketServer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'websocket:server:start',
    description: 'Start the WebSocket server for executing Symfony commands',
)]
class StartWebSocketServerCommand extends Command
{
    public function __construct(
        private readonly CommandExecutor $commandExecutor,
        private readonly KernelInterface $kernel,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('host', null, InputOption::VALUE_OPTIONAL, 'The host to bind to', '0.0.0.0')
            ->addOption('port', null, InputOption::VALUE_OPTIONAL, 'The port to bind to', 8080);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $host = $input->getOption('host');
        $port = (int)$input->getOption('port');

        $io->title('Starting WebSocket Server');
        $io->text([
            'WebSocket server is starting...',
            sprintf('Host: %s', $host),
            sprintf('Port: %d', $port),
            'Press Ctrl+C to stop the server',
        ]);

        try {
            // Start the WebSocket server
            WebSocketServer::start($host, $port, $this->commandExecutor, $this->kernel);
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error(sprintf('Error starting WebSocket server: %s', $e->getMessage()));
            return Command::FAILURE;
        }
    }
}
