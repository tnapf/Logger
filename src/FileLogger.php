<?php

namespace Tnapf\Logger;

use DateTimeImmutable;
use Tnapf\Logger\Exceptions\LoggerException;
use Tnapf\Logger\Interfaces\LoggerInterface;

class FileLogger implements LoggerInterface
{
    protected string $logFile;

    public function __construct(string $logFile)
    {
        $this->logFile = $logFile;
        if (!file_exists($logFile)) {
            touch($logFile);
        }
    }

    public function log(string $level, string $message, array $context = []): void
    {
        $formattedMessage = $this->formatMessage($level, $message, $context);
        file_put_contents($this->logFile, $formattedMessage, FILE_APPEND | LOCK_EX);
    }

    public function debug(string $message, array $context = []): void
    {
        $this->log('DEBUG', $message, $context);
    }

    public function info(string $message, array $context = []): void
    {
        $this->log('INFO', $message, $context);
    }

    public function notice(string $message, array $context = []): void
    {
        $this->log('NOTICE', $message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->log('WARNING', $message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->log('ERROR', $message, $context);
    }

    public function critical(string $message, array $context = []): void
    {
        $this->log('CRITICAL', $message, $context);
    }

    public function alert(string $message, array $context = []): void
    {
        $this->log('ALERT', $message, $context);
    }

    public function emergency(string $message, array $context = []): void
    {
        $this->log('EMERGENCY', $message, $context);
    }

    protected function formatMessage(string $level, string $message, array $context): string
    {
        $dateTime = new DateTimeImmutable();
        $timestamp = $dateTime->format('Y-m-d H:i:s') . '.' . sprintf('%03d', $dateTime->format('v'));
        $contextString = empty($context) ? '' : json_encode($context);
        return "[{$timestamp}] {$level}: {$message} {$contextString}" . PHP_EOL;
    }
}
