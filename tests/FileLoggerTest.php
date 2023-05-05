<?php

namespace Tests\Tnapf\Logger;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use Tnapf\Logger\Exceptions\CouldNotCreateResourceException;
use Tnapf\Logger\Exceptions\CouldNotWriteResourceException;
use Tnapf\Logger\FileLogger;

class FileLoggerTest extends TestCase
{
    protected vfsStreamDirectory $root;
    protected string $testLogFile;

    public function testDebug(): void
    {
        $logger = new FileLogger($this->testLogFile);
        $logger->debug('Debug message');
        $this->assertStringContainsString(
            LogLevel::DEBUG . ': Debug message',
            file_get_contents($this->testLogFile)
        );
    }

    public function testInfo(): void
    {
        $logger = new FileLogger($this->testLogFile);
        $logger->info('Info message');
        $this->assertStringContainsString(
            LogLevel::INFO . ': Info message',
            file_get_contents($this->testLogFile)
        );
    }

    public function testNotice(): void
    {
        $logger = new FileLogger($this->testLogFile);
        $logger->notice('Notice message');
        $this->assertStringContainsString(
            LogLevel::NOTICE . ': Notice message',
            file_get_contents($this->testLogFile)
        );
    }

    public function testWarning(): void
    {
        $logger = new FileLogger($this->testLogFile);
        $logger->warning('Warning message');
        $this->assertStringContainsString(
            LogLevel::WARNING . ': Warning message',
            file_get_contents($this->testLogFile)
        );
    }

    public function testError(): void
    {
        $logger = new FileLogger($this->testLogFile);
        $logger->error('Error message');
        $this->assertStringContainsString(
            LogLevel::ERROR . ': Error message',
            file_get_contents($this->testLogFile)
        );
    }

    public function testCritical(): void
    {
        $logger = new FileLogger($this->testLogFile);
        $logger->critical('Critical message');
        $this->assertStringContainsString(
            LogLevel::CRITICAL . ': Critical message',
            file_get_contents($this->testLogFile)
        );
    }

    public function testAlert(): void
    {
        $logger = new FileLogger($this->testLogFile);
        $logger->alert('Alert message');
        $this->assertStringContainsString(
            LogLevel::ALERT . ': Alert message',
            file_get_contents($this->testLogFile)
        );
    }

    public function testEmergency(): void
    {
        $logger = new FileLogger($this->testLogFile);
        $logger->emergency('Emergency message');
        $this->assertStringContainsString(
            LogLevel::EMERGENCY . ': Emergency message',
            file_get_contents($this->testLogFile)
        );
    }

    public function testDebugWithContext(): void
    {
        $logger = new FileLogger($this->testLogFile);
        $data = [
            'user_id' => 1,
            'user_name' => 'John Doe'
        ];
        $logger->debug('Debug message', $data);
        $this->assertStringContainsString(
            LogLevel::DEBUG . ': Debug message {"user_id":1,"user_name":"John Doe"}',
            file_get_contents($this->testLogFile)
        );
    }

    public function testInfoWithContext(): void
    {
        $logger = new FileLogger($this->testLogFile);
        $data = [
            'user_id' => 1,
            'user_name' => 'John Doe'
        ];
        $logger->info('Info message', $data);
        $this->assertStringContainsString(
            LogLevel::INFO . ': Info message {"user_id":1,"user_name":"John Doe"}',
            file_get_contents($this->testLogFile)
        );
    }

    public function testNoticeWithContext(): void
    {
        $logger = new FileLogger($this->testLogFile);
        $data = [
            'user_id' => 1,
            'user_name' => 'John Doe'
        ];
        $logger->notice('Notice message', $data);
        $this->assertStringContainsString(
            LogLevel::NOTICE . ': Notice message {"user_id":1,"user_name":"John Doe"}',
            file_get_contents($this->testLogFile)
        );
    }

    public function testWarningWithContext(): void
    {
        $logger = new FileLogger($this->testLogFile);
        $data = [
            'user_id' => 1,
            'user_name' => 'John Doe'
        ];
        $logger->warning('Warning message', $data);
        $this->assertStringContainsString(
            LogLevel::WARNING . ': Warning message {"user_id":1,"user_name":"John Doe"}',
            file_get_contents($this->testLogFile)
        );
    }

    public function testErrorWithContext(): void
    {
        $logger = new FileLogger($this->testLogFile);
        $data = [
            'user_id' => 1,
            'user_name' => 'John Doe'
        ];
        $logger->error('Error message', $data);
        $this->assertStringContainsString(
            LogLevel::ERROR . ': Error message {"user_id":1,"user_name":"John Doe"}',
            file_get_contents($this->testLogFile)
        );
    }

    public function testCriticalWithContext(): void
    {
        $logger = new FileLogger($this->testLogFile);
        $data = [
            'user_id' => 1,
            'user_name' => 'John Doe'
        ];
        $logger->critical('Critical message', $data);
        $this->assertStringContainsString(
            LogLevel::CRITICAL . ': Critical message {"user_id":1,"user_name":"John Doe"}',
            file_get_contents($this->testLogFile)
        );
    }

    public function testAlertWithContext(): void
    {
        $logger = new FileLogger($this->testLogFile);
        $data = [
            'user_id' => 1,
            'user_name' => 'John Doe'
        ];
        $logger->alert('Alert message', $data);
        $this->assertStringContainsString(
            LogLevel::ALERT . ': Alert message {"user_id":1,"user_name":"John Doe"}',
            file_get_contents($this->testLogFile)
        );
    }

    public function testEmergencyWithContext(): void
    {
        $logger = new FileLogger($this->testLogFile);
        $data = [
            'user_id' => 1,
            'user_name' => 'John Doe'
        ];
        $logger->emergency('Emergency message', $data);
        $this->assertStringContainsString(
            LogLevel::EMERGENCY . ': Emergency message {"user_id":1,"user_name":"John Doe"}',
            file_get_contents($this->testLogFile)
        );
    }

    public function testCannotCreateLogFile(): void
    {
        error_reporting(E_ALL & ~E_WARNING); // expecting a warning

        $this->expectException(CouldNotCreateResourceException::class);
        $this->expectExceptionMessage('Unable to create the log file: ');

        $invalidPath = '/nonexistent_directory/test_log_file.log';
        $logger = new FileLogger($invalidPath);
    }

    public function testCannotWriteToLogFile(): void
    {
        error_reporting(E_ALL & ~E_WARNING); // expecting a warning

        $this->expectException(CouldNotWriteResourceException::class);
        $this->expectExceptionMessage('Unable to write to the log file: ');

        $logger = new FileLogger($this->testLogFile);
        chmod($this->testLogFile, 0400);

        $logger->debug('Debug message into readonly file');
    }

    protected function setUp(): void
    {
        $this->root = vfsStream::setup('logs');
        $this->testLogFile = $this->root->url() . '/test_log_file.log';
    }
}
