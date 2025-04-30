<?php

namespace AlexPerez\SymfonyWebsocketCommandExecutor\Connection;

use AlexPerez\SymfonyWebsocketCommandExecutor\Output\WebSocketOutput;
use Ratchet\ConnectionInterface;
use Symfony\Component\Console\Application;

final readonly class ConnectionWrapper implements ConnectionInterface
{
    public function __construct(
        public Application $console,
        public WebSocketOutput $output,
        private ConnectionInterface $connection,
    ) {
    }

    function send($data): void
    {
        $this->connection->send($data);
    }

    function close(): void
    {
        $this->connection->close();
    }
}