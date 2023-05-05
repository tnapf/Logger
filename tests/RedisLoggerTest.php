<?php

namespace Tests\Tnapf\Logger;

use PHPUnit\Framework\TestCase;
use Tnapf\Logger\RedisLogger;
use Redis;
use Psr\Log\LogLevel;

class RedisLoggerTest extends TestCase
{
    protected Redis $redis;
    protected string $redisKey = 'test_logs';

    protected function setUp(): void
    {
        $this->redis = new Redis();
        $this->redis->connect('127.0.0.1', 6379);
        $this->redis->del($this->redisKey);
    }

    protected function tearDown(): void
    {
        $this->redis->del($this->redisKey);
    }

    public function testLog()
    {
        $logger = new RedisLogger($this->redis, $this->redisKey);

        $level = LogLevel::INFO;
        $message = 'Redis test message';
        $logger->log($level, $message);

        $logEntryJson = $this->redis->lindex($this->redisKey, 0);
        $this->assertNotNull($logEntryJson, 'Log entry not found in Redis list');

        $logEntry = json_decode($logEntryJson, true);
        $this->assertSame($level, $logEntry['level'], 'Log level mismatch');
        $this->assertSame($message, $logEntry['message'], 'Log message mismatch');
    }
}
