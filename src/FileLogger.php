<?php

namespace Tnapf\Logger;

use DateTimeImmutable;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Stringable;
use Tnapf\Logger\Exceptions\CouldNotCreateResourceException;
use Tnapf\Logger\Exceptions\CouldNotSetResourcePermissionException;
use Tnapf\Logger\Exceptions\CouldNotWriteResourceException;

class FileLogger implements LoggerInterface
{
    protected string $logFile;

    /**
     * @param bool $setSafe Set the log file permissions to 0644. Defaults to <strong>true</strong>.
     * @throws CouldNotCreateResourceException Throws an exception if the log file cannot be found nor created.
     */
    public function __construct(string $logFile, bool $setSafe = true, $permissions = 0644)
    {
        $this->logFile = $logFile;
        if (!file_exists($logFile) && !touch($logFile)) {
            throw new CouldNotCreateResourceException('Unable to create the log file: ' . $logFile);
        }
        if ($setSafe) {
            chmod($logFile, $permissions);
        }
    }

    /**
     * @throws CouldNotWriteResourceException
     */
    public function debug(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
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

    /**
     * @throws CouldNotWriteResourceException
     */
    public function info(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * @throws CouldNotWriteResourceException
     */
    public function notice(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * @throws CouldNotWriteResourceException
     */
    public function warning(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * @throws CouldNotWriteResourceException
     */
    public function error(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * @throws CouldNotWriteResourceException
     */
    public function critical(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * @throws CouldNotWriteResourceException
     */
    public function alert(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * @throws CouldNotWriteResourceException
     */
    public function emergency(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }
}
