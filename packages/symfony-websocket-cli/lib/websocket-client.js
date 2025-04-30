const WebSocket = require('ws');
const chalk = require('chalk');

class WebSocketClient {
  constructor(host, port) {
    this.host = host;
    this.port = port;
    this.ws = null;
    this.connected = false;
  }

  /**
   * Connect to the WebSocket server
   * @returns {Promise<void>} A promise that resolves when connected
   */
  connect() {
    return new Promise((resolve, reject) => {
      const url = `ws://${this.host}:${this.port}`;
      console.log(chalk.blue(`Connecting to ${url}...`));
      
      this.ws = new WebSocket(url);
      
      this.ws.on('open', () => {
        this.connected = true;
        console.log(chalk.green('Connected to Symfony WebSocket server'));
      });
      
      this.ws.on('message', (data) => {
        try {
          const message = JSON.parse(data);
          
          if (message.type === 'connection') {
            console.log(chalk.green(message.message));
            resolve();
          }
        } catch (error) {
          console.error('Error parsing message:', error);
        }
      });
      
      this.ws.on('error', (error) => {
        console.error(chalk.red(`WebSocket error: ${error.message}`));
        reject(error);
      });
      
      this.ws.on('close', () => {
        this.connected = false;
        console.log(chalk.yellow('Disconnected from Symfony WebSocket server'));
      });
      
      // Set a connection timeout
      setTimeout(() => {
        if (!this.connected) {
          reject(new Error('Connection timeout'));
          this.ws.terminate();
        }
      }, 5000);
    });
  }

  /**
   * Execute a Symfony command
   * @param {string} command The command to execute
   * @returns {Promise<void>} A promise that resolves when the command completes
   */
  executeCommand(command) {
    return new Promise((resolve, reject) => {
      if (!this.connected || !this.ws) {
        reject(new Error('Not connected to WebSocket server'));
        return;
      }
      
      console.log(chalk.blue(`Executing command: ${command}`));
      
      // Send the command to the server
      this.ws.send(JSON.stringify({ command }));
      
      // Handle command output
      const messageHandler = (data) => {
        try {
          const message = JSON.parse(data);
          
          if (message.type === 'stdout') {
            // Standard output
            process.stdout.write(message.data);
          } else if (message.type === 'stderr') {
            // Error output
            process.stderr.write(chalk.red(message.data));
          } else if (message.status === 'started') {
            // Command started
            console.log(chalk.yellow('Command execution started'));
          } else if (message.status === 'finished') {
            // Command finished
            console.log(chalk.green(`Command execution finished with exit code: ${message.exitCode}`));
            
            // Remove the message handler
            this.ws.removeListener('message', messageHandler);
            
            // Close the WebSocket connection
            this.ws.close();
            
            // Resolve the promise with the exit code
            resolve(message.exitCode);
          } else if (message.type === 'error') {
            // Server error
            console.error(chalk.red(`Server error: ${message.message}`));
            reject(new Error(message.message));
          }
        } catch (error) {
          console.error('Error parsing message:', error);
        }
      };
      
      // Add the message handler
      this.ws.on('message', messageHandler);
      
      // Set a command execution timeout
      setTimeout(() => {
        if (this.connected) {
          this.ws.removeListener('message', messageHandler);
          reject(new Error('Command execution timeout'));
          this.ws.close();
        }
      }, 60000); // 1 minute timeout
    });
  }
}

module.exports = WebSocketClient;