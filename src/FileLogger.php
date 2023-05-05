<?php

namespace Tnapf\Logger;

use DateTimeImmutable;
use Psr\Log\AbstractLogger;
use Stringable;
use Tnapf\Logger\Exceptions\CouldNotCreateResourceException;
use Tnapf\Logger\Exceptions\CouldNotWriteResourceException;

class FileLogger extends AbstractLogger
{
    protected string $logFile;

    /**
     * @param int $permissions The permissions to set on the log file. Defaults to 0644.
     * @throws CouldNotCreateResourceException Throws an exception if the log file cannot be found nor created.
     */
    public function __construct(string $logFile, int $permissions = 0644)
    {
        $this->logFile = $logFile;
        if (!file_exists($logFile) && !touch($logFile)) {
            throw new CouldNotCreateResourceException('Unable to create the log file: ' . $logFile);
        }
        chmod($logFile, $permissions);
    }

    /**
     * @throws CouldNotWriteResourceException Throws an exception if the log file cannot be written to.
     */
    public function log(mixed $level, string|Stringable $message, array $context = []): void
    {
        $formattedMessage = $this->formatMessage($level, $message, $context);
        if (file_put_contents($this->logFile, $formattedMessage, FILE_APPEND | LOCK_EX) === false) {
            throw new CouldNotWriteResourceException('Unable to write to the log file: ' . $this->logFile);
        }
    }

    protected function formatMessage(string $level, string $message, array $context): string
    {
        $dateTime = new DateTimeImmutable();
        $timestamp = $dateTime->format('Y-m-d H:i:s') . '.' . sprintf('%03d', $dateTime->format('v'));
        $contextString = empty($context) ? '' : ' ' . json_encode($context);
        return "[{$timestamp}] {$level}: {$message}{$contextString}" . PHP_EOL;
    }
}
