<?php

namespace Tnapf\Logger;

use DateTimeImmutable;
use PDO;
use Psr\Log\AbstractLogger;
use Stringable;

class DatabaseLogger extends AbstractLogger
{
    public function __construct(protected PDO $pdo, protected string $tableName = 'logs')
    {
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
}
