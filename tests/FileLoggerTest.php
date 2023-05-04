<?php

namespace Tests\Tnapf\Logger;

use PHPUnit\Framework\TestCase;
use Tnapf\Logger\FileLogger;

class FileLoggerTest extends TestCase
{
    protected string $testLogFile;

    protected function setUp(): void
    {
        $this->testLogFile = sys_get_temp_dir() . '/test_log_file.log';
    }

    protected function tearDown(): void
    {
        if (file_exists($this->testLogFile)) {
            unlink($this->testLogFile);
        }
    }

    public function testDebug(): void
    {
        $logger = new FileLogger($this->testLogFile);
        $logger->debug('Debug message');
        $this->assertStringContainsString('DEBUG: Debug message', file_get_contents($this->testLogFile));
    }

    public function testInfo(): void
    {
        $logger = new FileLogger($this->testLogFile);
        $logger->info('Info message');
        $this->assertStringContainsString('INFO: Info message', file_get_contents($this->testLogFile));
    }

    public function testNotice(): void
    {
        $logger = new FileLogger($this->testLogFile);
        $logger->notice('Notice message');
        $this->assertStringContainsString('NOTICE: Notice message', file_get_contents($this->testLogFile));
    }

    public function testWarning(): void
    {
        $logger = new FileLogger($this->testLogFile);
        $logger->warning('Warning message');
        $this->assertStringContainsString('WARNING: Warning message', file_get_contents($this->testLogFile));
    }

    public function testError(): void
    {
        $logger = new FileLogger($this->testLogFile);
        $logger->error('Error message');
        $this->assertStringContainsString('ERROR: Error message', file_get_contents($this->testLogFile));
    }

    public function testCritical(): void
    {
        $logger = new FileLogger($this->testLogFile);
        $logger->critical('Critical message');
        $this->assertStringContainsString('CRITICAL: Critical message', file_get_contents($this->testLogFile));
    }

    public function testAlert(): void
    {
        $logger = new FileLogger($this->testLogFile);
        $logger->alert('Alert message');
        $this->assertStringContainsString('ALERT: Alert message', file_get_contents($this->testLogFile));
    }

    public function testEmergency(): void
    {
        $logger = new FileLogger($this->testLogFile);
        $logger->emergency('Emergency message');
        $this->assertStringContainsString('EMERGENCY: Emergency message', file_get_contents($this->testLogFile));
    }
}
