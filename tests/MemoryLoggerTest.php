<?php

namespace Tests\Tnapf\Logger;

use Psr\Log\LogLevel;
use Tnapf\Logger\MemoryLogger;
use PHPUnit\Framework\TestCase;

class MemoryLoggerTest extends TestCase
{
    public function testLogAndRetrieveLogs()
    {
        $memoryLogger = new MemoryLogger();

        $memoryLogger->info('Information message');
        $memoryLogger->error('Error message', ['error_code' => 123]);

        $logs = $memoryLogger->getLogs();

        $this->assertCount(2, $logs);

        $this->assertEquals('Information message', $logs[0]['message']);
        $this->assertEquals(LogLevel::INFO, $logs[0]['level']);

        $this->assertEquals('Error message', $logs[1]['message']);
        $this->assertEquals(LogLevel::ERROR, $logs[1]['level']);
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
