<?php

namespace Tnapf\Logger;

use DateTimeImmutable;
use PDO;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Stringable;

class DatabaseLogger implements LoggerInterface
{
    protected PDO $pdo;
    protected string $tableName;

    public function __construct(PDO $pdo, string $tableName = 'logs')
    {
        $this->pdo = $pdo;
        $this->tableName = $tableName;
    }

    public function debug(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    public function log(mixed $level, string|Stringable $message, array $context = []): void
    {
        $formattedMessage = $this->formatMessage($message, $context);
        $this->writeToDatabase($level, $formattedMessage);
    }

    protected function formatMessage(string $message, array $context): string
    {
        $contextString = empty($context) ? '' : ' ' . json_encode($context);
        return "{$message}{$contextString}";
    }

    protected function writeToDatabase(string $level, string $formattedMessage): void
    {
        $sql = "INSERT INTO {$this->tableName} (level, message, created_at) VALUES (:level, :message, :created_at)";
        $stmt = $this->pdo->prepare($sql);
        $res = $stmt->execute([
            ':level' => $level,
            ':message' => $formattedMessage,
            ':created_at' => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
        ]);
    }

    public function info(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    public function notice(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    public function warning(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    public function error(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    public function critical(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    public function alert(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    public function emergency(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }
}
