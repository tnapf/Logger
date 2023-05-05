<?php

namespace Tests\Tnapf\Logger;

use PDO;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use Tnapf\Logger\DatabaseLogger;

class DatabaseLoggerTest extends TestCase
{
    protected PDO $pdo;

    public function testDebug(): void
    {
        $logger = new DatabaseLogger($this->pdo, 'test_logs');
        $logger->debug('Debug message');
        $logEntry = $this->getLastLogEntry();
        $this->assertSame(LogLevel::DEBUG, $logEntry['level']);
        $this->assertSame('Debug message', $logEntry['message']);
    }

    protected function getLastLogEntry(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM test_logs ORDER BY id DESC LIMIT 1');
        $logEntry = $stmt->fetch(PDO::FETCH_ASSOC);
        return $logEntry ?: [];
    }

    public function testInfo(): void
    {
        $logger = new DatabaseLogger($this->pdo, 'test_logs');
        $logger->info('Info message');
        $logEntry = $this->getLastLogEntry();
        $this->assertSame(LogLevel::INFO, $logEntry['level']);
        $this->assertSame('Info message', $logEntry['message']);
    }

    public function testNotice(): void
    {
        $logger = new DatabaseLogger($this->pdo, 'test_logs');
        $logger->notice('Notice message');
        $logEntry = $this->getLastLogEntry();
        $this->assertSame(LogLevel::NOTICE, $logEntry['level']);
        $this->assertSame('Notice message', $logEntry['message']);
    }

    public function testWarning(): void
    {
        $logger = new DatabaseLogger($this->pdo, 'test_logs');
        $logger->warning('Warning message');
        $logEntry = $this->getLastLogEntry();
        $this->assertSame(LogLevel::WARNING, $logEntry['level']);
        $this->assertSame('Warning message', $logEntry['message']);
    }

    public function testError(): void
    {
        $logger = new DatabaseLogger($this->pdo, 'test_logs');
        $logger->error('Error message');
        $logEntry = $this->getLastLogEntry();
        $this->assertSame(LogLevel::ERROR, $logEntry['level']);
        $this->assertSame('Error message', $logEntry['message']);
    }

    public function testCritical(): void
    {
        $logger = new DatabaseLogger($this->pdo, 'test_logs');
        $logger->critical('Critical message');
        $logEntry = $this->getLastLogEntry();
        $this->assertSame(LogLevel::CRITICAL, $logEntry['level']);
        $this->assertSame('Critical message', $logEntry['message']);
    }

    public function testAlert(): void
    {
        $logger = new DatabaseLogger($this->pdo, 'test_logs');
        $logger->alert('Alert message');
        $logEntry = $this->getLastLogEntry();
        $this->assertSame(LogLevel::ALERT, $logEntry['level']);
        $this->assertSame('Alert message', $logEntry['message']);
    }

    public function testEmergency(): void
    {
        $logger = new DatabaseLogger($this->pdo, 'test_logs');
        $logger->emergency('Emergency message');
        $logEntry = $this->getLastLogEntry();
        $this->assertSame(LogLevel::EMERGENCY, $logEntry['level']);
        $this->assertSame('Emergency message', $logEntry['message']);
    }

    public function testDebugWithContext(): void
    {
        $logger = new DatabaseLogger($this->pdo, 'test_logs');
        $data = [
            'user_id' => 1,
            'user_name' => 'John Doe'
        ];
        $logger->debug('Debug message', $data);
        $logEntry = $this->getLastLogEntry();
        $this->assertSame(LogLevel::DEBUG, $logEntry['level']);
        $this->assertSame(
            'Debug message {"user_id":1,"user_name":"John Doe"}',
            $logEntry['message']
        );
    }

    public function testInfoWithContext(): void
    {
        $logger = new DatabaseLogger($this->pdo, 'test_logs');
        $data = [
            'user_id' => 1,
            'user_name' => 'John Doe'
        ];
        $logger->info('Info message', $data);
        $logEntry = $this->getLastLogEntry();
        $this->assertSame(LogLevel::INFO, $logEntry['level']);
        $this->assertSame(
            'Info message {"user_id":1,"user_name":"John Doe"}',
            $logEntry['message']
        );
    }

    public function testNoticeWithContext(): void
    {
        $logger = new DatabaseLogger($this->pdo, 'test_logs');
        $data = [
            'user_id' => 1,
            'user_name' => 'John Doe'
        ];
        $logger->notice('Notice message', $data);
        $logEntry = $this->getLastLogEntry();
        $this->assertSame(LogLevel::NOTICE, $logEntry['level']);
        $this->assertSame(
            'Notice message {"user_id":1,"user_name":"John Doe"}',
            $logEntry['message']
        );
    }

    public function testWarningWithContext(): void
    {
        $logger = new DatabaseLogger($this->pdo, 'test_logs');
        $data = [
            'user_id' => 1,
            'user_name' => 'John Doe'
        ];
        $logger->warning('Warning message', $data);
        $logEntry = $this->getLastLogEntry();
        $this->assertSame(LogLevel::WARNING, $logEntry['level']);
        $this->assertSame(
            'Warning message {"user_id":1,"user_name":"John Doe"}',
            $logEntry['message']
        );
    }

    public function testErrorWithContext(): void
    {
        $logger = new DatabaseLogger($this->pdo, 'test_logs');
        $data = [
            'user_id' => 1,
            'user_name' => 'John Doe'
        ];
        $logger->error('Error message', $data);
        $logEntry = $this->getLastLogEntry();
        $this->assertSame(LogLevel::ERROR, $logEntry['level']);
        $this->assertSame(
            'Error message {"user_id":1,"user_name":"John Doe"}',
            $logEntry['message']
        );
    }

    public function testCriticalWithContext(): void
    {
        $logger = new DatabaseLogger($this->pdo, 'test_logs');
        $data = [
            'user_id' => 1,
            'user_name' => 'John Doe'
        ];
        $logger->critical('Critical message', $data);
        $logEntry = $this->getLastLogEntry();
        $this->assertSame(LogLevel::CRITICAL, $logEntry['level']);
        $this->assertSame(
            'Critical message {"user_id":1,"user_name":"John Doe"}',
            $logEntry['message']
        );
    }

    public function testAlertWithContext(): void
    {
        $logger = new DatabaseLogger($this->pdo, 'test_logs');
        $data = [
            'user_id' => 1,
            'user_name' => 'John Doe'
        ];
        $logger->alert('Alert message', $data);
        $logEntry = $this->getLastLogEntry();
        $this->assertSame(LogLevel::ALERT, $logEntry['level']);
        $this->assertSame(
            'Alert message {"user_id":1,"user_name":"John Doe"}',
            $logEntry['message']
        );
    }

    public function testEmergencyWithContext(): void
    {
        $logger = new DatabaseLogger($this->pdo, 'test_logs');
        $data = [
            'user_id' => 1,
            'user_name' => 'John Doe'
        ];
        $logger->emergency('Emergency message', $data);
        $logEntry = $this->getLastLogEntry();
        $this->assertSame(LogLevel::EMERGENCY, $logEntry['level']);
        $this->assertSame(
            'Emergency message {"user_id":1,"user_name":"John Doe"}',
            $logEntry['message']
        );
    }

    protected function setUp(): void
    {
        $this->pdo = new PDO('mysql:host=127.0.0.1;dbname=tnapf', 'root', 'password');
        $this->pdo->exec('
            CREATE TABLE IF NOT EXISTS test_logs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                level VARCHAR(20) NOT NULL,
                message TEXT NOT NULL,
                created_at DATETIME NOT NULL
            )
        ');
    }

    protected function tearDown(): void
    {
        $this->pdo->exec('DROP TABLE IF EXISTS test_logs');
    }
}
