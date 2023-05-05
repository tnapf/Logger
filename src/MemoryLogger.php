<?php

namespace Tnapf\Logger;

use Stringable;

class MemoryLogger extends \Psr\Log\AbstractLogger
{
    protected array $logs = [];

    public function log(mixed $level, string|Stringable $message, array $context = []): void
    {
        $logEntry = [
            'level' => $level,
            'message' => (string)$message,
            'context' => $context,
            'timestamp' => time()
        ];

        $this->logs[] = $logEntry;
    }

    public function getLogs(): array
    {
        return $this->logs;
    }

    public function clearLogs(): void
    {
        $this->logs = [];
    }
}
