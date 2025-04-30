#!/usr/bin/env node

const { program } = require('commander');
const WebSocketClient = require('../lib/websocket-client');
const package = require('../package.json');

// Configure the CLI program
program
  .name('symfony-ws')
  .description('Node.js CLI for Symfony WebSocket Command Executor')
  .version(package.version)
  .option('-h, --host <host>', 'WebSocket server host', 'localhost')
  .option('-p, --port <port>', 'WebSocket server port', '8080')
  .argument('[command...]', 'Symfony command to execute')
  .action(async (commandArgs, options) => {
    const command = commandArgs.join(' ');
    
    if (!command) {
      console.log('No command specified. Use --help for usage information.');
      process.exit(1);
    }

    const client = new WebSocketClient(options.host, options.port);
    
    try {
      await client.connect();
      await client.executeCommand(command);
    } catch (error) {
      console.error('Error:', error.message);
      process.exit(1);
    }
  });

program.parse();