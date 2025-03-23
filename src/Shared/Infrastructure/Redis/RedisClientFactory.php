<?php
namespace AlexPerez\CoffeeMachine\Shared\Infrastructure\Redis;

use Predis\Client;

final readonly class RedisClientFactory
{
    public function __construct(
        private string $host,
        private string $port,
        private string $scheme = 'tcp',
    )
    {

    }

    public function create(): Client
    {
        return new Client([
            'scheme' => $this->scheme,
            'host'   => $this->host,
            'port'   => $this->port,
        ]);
    }
}