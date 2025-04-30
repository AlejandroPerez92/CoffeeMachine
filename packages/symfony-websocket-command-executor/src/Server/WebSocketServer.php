<?php

namespace AlexPerez\SymfonyWebsocketCommandExecutor\Server;

use AlexPerez\SymfonyWebsocketCommandExecutor\Command\CommandExecutor;
use AlexPerez\SymfonyWebsocketCommandExecutor\Connection\ConnectionWrapper;
use AlexPerez\SymfonyWebsocketCommandExecutor\Output\WebSocketOutput;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class WebSocketServer implements MessageComponentInterface
{
    /** @var array<string,ConnectionWrapper> */
    private array $clients;

    public function __construct(
        private readonly CommandExecutor $commandExecutor,
        private readonly KernelInterface $kernel,
    ) {
        $this->clients = [];
    }

    public function onOpen(ConnectionInterface $conn): void
    {
        $app = new Application($this->kernel);
        $app->setAutoExit(false);

        $this->clients[spl_object_id($conn)] = new ConnectionWrapper(
            $app,
            new WebSocketOutput(
                $conn,
                OutputInterface::VERBOSITY_NORMAL,
                new OutputFormatter(true),
            ),
            $conn,
        );

        $conn->send(json_encode([
            'type' => 'connection',
            'message' => 'Connected to Symfony Command Executor'
        ]));
    }

    public function onMessage(ConnectionInterface $from, $msg): void
    {
        $data = json_decode($msg, true);

        // Handle new command execution
        if (!isset($data['command'])) {
            $from->send(json_encode([
                'type' => 'error',
                'message' => 'No command specified'
            ]));
            return;
        }

        // Execute the command and send the output
        $this->commandExecutor->execute(
            $data['command'],
            $this->clients[spl_object_id($from)],
        );
    }

    public function onClose(ConnectionInterface $conn): void
    {
        unset($this->clients[spl_object_id($conn)]);;
    }

    public function onError(ConnectionInterface $conn, \Exception $e): void
    {
        $conn->send(json_encode([
            'type' => 'error',
            'message' => $e->getMessage()
        ]));
        $conn->close();
    }

    public static function start(string $host, int $port, CommandExecutor $commandExecutor, KernelInterface $kernel): void
    {
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new self($commandExecutor, $kernel)
                )
            ),
            $port,
            $host
        );

        echo "WebSocket server started on $host:$port\n";
        $server->run();
    }
}
