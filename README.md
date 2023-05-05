# Tnapf/Logger

Totally Not Another PHP Framework's Logger Component

# Table of Contents

- [Installation](#installation)
- [Usage](#usage)
    - [Common methods](#common-methods)
    - [FileLogger](#filelogger)
    - [DatabaseLogger](#databaselogger)
    - [NullLogger](#nulllogger)
    - [CompositeLogger](#compositelogger)
- [License](LICENSE)

# Installation

```
composer require tnapf/logger
```

# Usage

## Common methods

Every class includes the following methods:

```php
log(mixed $level, string|Stringable $message, array $context = [])
debug(string|Stringable $message, array $context = [])
info(string|Stringable $message, array $context = [])
notice(string|Stringable $message, array $context = [])
warning(string|Stringable $message, array $context = [])
error(string|Stringable $message, array $context = [])
critical(string|Stringable $message, array $context = [])
alert(string|Stringable $message, array $context = [])
emergency(string|Stringable $message, array $context = [])
```

## FileLogger

The `FileLogger` class writes logs to a specified file.

### Usage

```php
use Tnapf\Logger\FileLogger;

$logFile = '/path/to/your/logfile.log';
$logger = new FileLogger($logFile);

$logger->debug('This is a debug message');
$logger->debug('This is a debug message with context', ['foo' => 'bar']);
```

### Methods

```php
__construct(string $logFile, $permissions = 0644)
```

## DatabaseLogger

The `DatabaseLogger` class writes logs to a database table using a PDO connection.

### Usage

```php
use Tnapf\Logger\DatabaseLogger;
use PDO;

$pdo = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
$logger = new DatabaseLogger($pdo);
// or
$logger = new DatabaseLogger($pdo, "log_table_name");

$logger->info('This is an info message');
$logger->info('This is an info message with context', ['foo' => 'bar']);
```

### Methods

```php
__construct(PDO $pdo, string $tableName = 'logs')
```

## NullLogger

The `NullLogger` class is a no-operation logger that discards log messages.

### Usage

```php
use Tnapf\Logger\NullLogger;

$logger = new NullLogger();
$logger->warning('This log message will be ignored.');
```

## CompositeLogger

The `CompositeLogger` class combines multiple loggers and forwards log messages to all of them.

### Usage

```php
use Tnapf\Logger\CompositeLogger;
use Tnapf\Logger\FileLogger;
use Tnapf\Logger\DatabaseLogger;
use PDO;

$logFile = '/path/to/your/logfile.log';
$fileLogger = new FileLogger($logFile);

$pdo = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
$databaseLogger = new DatabaseLogger($pdo);

$compositeLogger = new CompositeLogger([$fileLogger]);
$compositeLogger->addLogger($databaseLogger);

$compositeLogger->critical('This message will be logged to both the file and the database.');
```

### Methods

```php
__construct(LoggerInterface[] $loggers = [])
addLogger(LoggerInterface $logger): void
```

## MemoryLogger

The `MemoryLogger` class stores logs in memory without persisting them to files or outputting them to the command line.

### Usage

```php
<?php

use Tnapf\Logger\MemoryLogger;

$memoryLogger = new MemoryLogger();

$memoryLogger->info('Information message');
$memoryLogger->error('Error message', ['error_code' => 123]);

// Get all logs
$logs = $memoryLogger->getLogs();

// Clear logs
$memoryLogger->clearLogs();
```

### Methods

```php
getLogs(): array
clearLogs(): void
```

## RedisLogger

The `RedisLogger` class writes logs to a Redis list. It requires the php-redis extension.

### Usage

```php
<?php

require_once 'vendor/autoload.php';

use Tnapf\Logger\RedisLogger;
use Redis;

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

$logger = new RedisLogger($redis, 'my_log_key');

$logger->info('This is an info message');
$logger->error('This is an error message', ['error_code' => 123]);
```

### Methods

```php
__construct(Redis $redis, string $key = 'logger')
```

# License

MIT License

Copyright (c) 2023 Totally Not Another PHP Framework

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
