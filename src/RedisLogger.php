<?php

namespace Tnapf\Logger;

use DateTimeImmutable;
use Psr\Log\AbstractLogger;
use Redis;
use RedisException;
use Stringable;

class RedisLogger extends AbstractLogger
{
    protected Redis $redis;
    protected string $key;

    /**
     * @param string $key The key to use for the Redis list. Defaults to 'logger'.
     */
    public function __construct(Redis $redis, string $key = 'logger')
    {
        $this->redis = $redis;
        $this->key = $key;
    }

    /**
     * @throws RedisException if the connection to the Redis server fails.
     */
    public function log(mixed $level, string|Stringable $message, array $context = []): void
    {
        $logEntryJson = $this->formatMessage($level, $message, $context);
        $this->redis->rpush($this->key, $logEntryJson);
    }

    protected function formatMessage(string|Stringable $level, string $message, array $context): string
    {
        $dateTime = new DateTimeImmutable();
        $timestamp = $dateTime->format('Y-m-d H:i:s') . '.' . sprintf('%03d', $dateTime->format('v'));
        $logEntry = compact('timestamp', 'level', 'message', 'context');

        return json_encode($logEntry);
    }
}
