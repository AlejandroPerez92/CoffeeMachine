<?php

namespace AlexPerez\SymfonyWebsocketCommandExecutor\Output;

use Ratchet\ConnectionInterface;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WebSocketOutput implements OutputInterface
{

    public function __construct(
        private ConnectionInterface $connection,
        private int $verbosity = OutputInterface::VERBOSITY_NORMAL,
        private OutputFormatterInterface $formatter,
    ) {
    }

    public function write($messages, bool $newline = false, int $options = 0): void
    {
        $this->doWrite($messages, $newline, $options);
    }

    public function writeln($messages, int $options = 0): void
    {
        $this->doWrite($messages, true, $options);
    }

    private function doWrite($messages, bool $newline, int $options): void
    {
        // Skip empty messages
        if (null === $messages || '' === $messages) {
            return;
        }

        // Convert to array if it's a string
        $messages = \is_array($messages) ? $messages : [$messages];

        // Skip messages above the current verbosity level
        if ($options > $this->verbosity) {
            return;
        }

        $formatted = [];
        foreach ($messages as $message) {
            if ($options & OutputInterface::OUTPUT_RAW) {
                $formatted[] = $message;
            } else {
                $formatted[] = $this->formatter->format($message);
            }
        }

        $message = implode($newline ? PHP_EOL : '', $formatted);
        if ($newline) {
            $message .= PHP_EOL;
        }

        // Send the output to the client
        $this->connection->send(json_encode([
            'type' => 'stdout',
            'data' => $message,
        ]));
    }

    public function setVerbosity(int $level): void
    {
        $this->verbosity = $level;
    }

    public function getVerbosity(): int
    {
        return $this->verbosity;
    }

    public function isQuiet(): bool
    {
        return OutputInterface::VERBOSITY_QUIET === $this->verbosity;
    }

    public function isVerbose(): bool
    {
        return OutputInterface::VERBOSITY_VERBOSE <= $this->verbosity;
    }

    public function isVeryVerbose(): bool
    {
        return OutputInterface::VERBOSITY_VERY_VERBOSE <= $this->verbosity;
    }

    public function isDebug(): bool
    {
        return OutputInterface::VERBOSITY_DEBUG <= $this->verbosity;
    }

    public function setDecorated(bool $decorated): void
    {
        $this->formatter->setDecorated($decorated);
    }

    public function isDecorated(): bool
    {
        return $this->formatter->isDecorated();
    }

    public function setFormatter(OutputFormatterInterface $formatter): void
    {
        $this->formatter = $formatter;
    }

    public function getFormatter(): OutputFormatterInterface
    {
        return $this->formatter;
    }

    public function sendFinishedStatus(int $exitCode): void
    {
        $this->connection->send(json_encode([
            'status' => 'finished',
            'exitCode' => $exitCode,
        ]));
    }

    public function sendStartedStatus(): void
    {
        $this->connection->send(json_encode([
            'status' => 'started',
        ]));
    }

    public function sendErrorMessage(string $message): void
    {
        $this->connection->send(json_encode([
            'type' => 'stderr',
            'data' => $message,
        ]));
    }
}
