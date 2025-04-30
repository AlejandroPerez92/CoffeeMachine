# Symfony WebSocket CLI

A Node.js CLI application for interacting with the Symfony WebSocket Command Executor.

## Installation

```bash
# Install globally
npm install -g symfony-websocket-cli

# Or use npx
npx symfony-websocket-cli [options] [command]
```

## Usage

```bash
# Basic usage
symfony-ws [options] [command]

# Examples
symfony-ws app:some-command
symfony-ws app:some-command --option=value
symfony-ws --host=192.168.1.100 --port=8081 app:some-command

# Get help
symfony-ws --help
```

## Options

- `-h, --host <host>`: WebSocket server host (default: "localhost")
- `-p, --port <port>`: WebSocket server port (default: "8080")
- `--help`: Display help information
- `--version`: Display version information

## How It Works

This CLI application connects to a Symfony WebSocket server that can execute Symfony console commands. When you run a command through this CLI, it:

1. Connects to the WebSocket server
2. Sends the command to be executed
3. Streams the command output back to your terminal
4. Shows the exit code when the command completes

## Development

```bash
# Clone the repository
git clone <repository-url>

# Install dependencies
cd symfony-websocket-cli
npm install

# Run locally
node bin/symfony-ws.js [command]
```

## License

MIT