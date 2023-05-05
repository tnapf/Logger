<?php

namespace Tnapf\Logger;

use DateTimeImmutable;
use Psr\Log\AbstractLogger;
use Stringable;

class MemoryLogger extends AbstractLogger
{
    protected array $logs = [];

    public function log(mixed $level, string|Stringable $message, array $context = []): void
    {
        $logEntry = $this->formatMessage($level, $message, $context);
        $this->logs[] = $logEntry;
    }

    protected function formatMessage(string|Stringable $level, string $message, array $context): array
    {
        $dateTime = new DateTimeImmutable();
        $timestamp = $dateTime->format('Y-m-d H:i:s') . '.' . sprintf('%03d', $dateTime->format('v'));

        return compact('level', 'message', 'context', 'timestamp');
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
