<?php

namespace Tests\Tnapf\Logger;

use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use Tnapf\Logger\MemoryLogger;

class MemoryLoggerTest extends TestCase
{
    public function testLogAndRetrieveLogs()
    {
        $memoryLogger = new MemoryLogger();

        $memoryLogger->info('Information message');
        $memoryLogger->error('Error message', ['error_code' => 123]);

        $logs = $memoryLogger->getLogs();

        $this->assertCount(2, $logs);

        $this->assertSame('Information message', $logs[0]['message']);
        $this->assertSame(LogLevel::INFO, $logs[0]['level']);

        $this->assertSame('Error message', $logs[1]['message']);
        $this->assertSame(LogLevel::ERROR, $logs[1]['level']);
        $this->assertEquals(['error_code' => 123], $logs[1]['context']);
    }

    public function testClearLogs()
    {
        $memoryLogger = new MemoryLogger();

        $memoryLogger->info('Information message');
        $memoryLogger->error('Error message', ['error_code' => 123]);

        $memoryLogger->clearLogs();

        $logs = $memoryLogger->getLogs();

        $this->assertCount(0, $logs);
    }
}
