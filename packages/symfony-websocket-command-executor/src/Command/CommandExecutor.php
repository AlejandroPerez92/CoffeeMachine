<?php

namespace AlexPerez\SymfonyWebsocketCommandExecutor\Command;

use AlexPerez\SymfonyWebsocketCommandExecutor\Connection\ConnectionWrapper;
use Symfony\Component\Console\Input\StringInput;

class CommandExecutor
{
    /**
     * Execute a Symfony command and handle its output
     *
     * @param string $command The command to execute (without 'bin/console')
     * @param ConnectionWrapper $connection WebSocket connection to send output to
     * @param string|null $input Optional input for interactive commands
     * @return void
     */
    public function execute(string $commandString, ConnectionWrapper $connection, ?string $input = null): void
    {
        // Send the started status
        $connection->output->sendStartedStatus();

        $command = new StringInput($commandString);

        try {
            // Execute the command
            $exitCode = $connection->console->run($command, $connection->output);

            // Send the final status
            $connection->output->sendFinishedStatus($exitCode);
        } catch (\Exception $e) {
            // Send the error to the client
            $connection->output->sendErrorMessage($e->getMessage());

            // Send the final status
            $connection->output->sendFinishedStatus(1);
        }
    }

}
