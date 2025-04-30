# Symfony WebSocket Command Executor

A Symfony bundle that allows executing Symfony console commands through WebSocket with support for interactive commands.

## Installation

### 1. Add the package to your composer.json

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "./packages/symfony-websocket-command-executor"
        }
    ],
    "require": {
        "alexperez/symfony-websocket-command-executor": "*"
    }
}
```

### 2. Install the package

```bash
composer require alexperez/symfony-websocket-command-executor
```

### 3. Register the bundle in your `config/bundles.php`

```php
return [
    // ...
    AlexPerez\SymfonyWebsocketCommandExecutor\SymfonyWebsocketCommandExecutorBundle::class => ['all' => true],
];
```

## Usage

### Starting the WebSocket Server

Start the WebSocket server using the provided console command:

```bash
bin/console websocket:server:start
```

By default, the server will listen on `0.0.0.0:8080`. You can customize the host and port:

```bash
bin/console websocket:server:start --host=127.0.0.1 --port=8888
```

### Connecting to the WebSocket Server

You can connect to the WebSocket server using any WebSocket client. Here's an example using JavaScript:

```javascript
const socket = new WebSocket('ws://localhost:8080');

socket.onopen = function(e) {
  console.log('Connected to WebSocket server');
};

socket.onmessage = function(event) {
  const data = JSON.parse(event.data);
  console.log('Received:', data);
};

socket.onclose = function(event) {
  console.log('WebSocket connection closed');
};

socket.onerror = function(error) {
  console.error('WebSocket error:', error);
};
```

### Executing Commands

To execute a command, send a JSON message with the command to execute:

```javascript
socket.send(JSON.stringify({
  command: 'cache:clear'
}));
```

### Interactive Commands

For interactive commands that require input, you can send input to a running process:

1. First, execute the command:

```javascript
socket.send(JSON.stringify({
  command: 'make:entity'
}));
```

2. The server will respond with a process ID:

```json
{
  "processId": "cmd_5f8a7b3c9d4e2",
  "status": "started"
}
```

3. When the command asks for input, send the input with the process ID:

```javascript
socket.send(JSON.stringify({
  processId: "cmd_5f8a7b3c9d4e2",
  input: "User"
}));
```

## Response Format

The server sends JSON responses with the following format:

### Command Started

```json
{
  "processId": "cmd_5f8a7b3c9d4e2",
  "status": "started"
}
```

### Command Output

```json
{
  "processId": "cmd_5f8a7b3c9d4e2",
  "type": "stdout",
  "data": "Command output..."
}
```

### Command Error

```json
{
  "processId": "cmd_5f8a7b3c9d4e2",
  "type": "stderr",
  "data": "Error message..."
}
```

### Command Finished

```json
{
  "processId": "cmd_5f8a7b3c9d4e2",
  "status": "finished",
  "exitCode": 0
}
```

## License

MIT