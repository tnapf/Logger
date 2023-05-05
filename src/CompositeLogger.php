<?php

namespace Tnapf\Logger;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Stringable;

class CompositeLogger extends AbstractLogger
{
    /**
     * @param LoggerInterface[] $loggers
     */
    public function __construct(private array $loggers = [])
    {
    }

    public function addLogger(LoggerInterface $logger): void
    {
        $this->loggers[] = $logger;
    }

    public function log(mixed $level, string|Stringable $message, array $context = []): void
    {
        foreach ($this->loggers as $logger) {
            $logger->log($level, $message, $context);
        }
    }
}
