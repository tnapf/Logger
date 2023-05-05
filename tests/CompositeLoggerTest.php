<?php

namespace Tests\Tnapf\Logger;

use PDO;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use Tnapf\Logger\CompositeLogger;
use Tnapf\Logger\DatabaseLogger;
use Tnapf\Logger\Exceptions\LoggerException;
use Tnapf\Logger\FileLogger;

class CompositeLoggerTest extends TestCase
{
    protected string $testLogFile;
    protected PDO $pdo;

    /**
     * @throws LoggerException
     */
    public function testDebugLogToBothFileAndDatabase(): void
    {
        $fileLogger = new FileLogger($this->testLogFile);
        $databaseLogger = new DatabaseLogger($this->pdo, 'test_logs');
        $compositeLogger = new CompositeLogger([$fileLogger]);
        $compositeLogger->addLogger($databaseLogger);

        $compositeLogger->debug('Composite debug message');

        $this->assertStringContainsString(
            LogLevel::DEBUG . ': Composite debug message',
            file_get_contents($this->testLogFile)
        );

        $logEntry = $this->getLastLogEntry();

        $this->assertSame(LogLevel::DEBUG, $logEntry['level']);
        $this->assertSame('Composite debug message', $logEntry['message']);
    }

    protected function getLastLogEntry(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM test_logs ORDER BY id DESC LIMIT 1');
        $logEntry = $stmt->fetch(PDO::FETCH_ASSOC);
        return $logEntry ?: [];
    }

    /**
     * @throws LoggerException
     */
    public function testInfoLogToBothFileAndDatabase(): void
    {
        $fileLogger = new FileLogger($this->testLogFile);
        $databaseLogger = new DatabaseLogger($this->pdo, 'test_logs');
        $compositeLogger = new CompositeLogger([$fileLogger]);
        $compositeLogger->addLogger($databaseLogger);

        $compositeLogger->info('Composite info message');

        $this->assertStringContainsString(
            LogLevel::INFO . ': Composite info message',
            file_get_contents($this->testLogFile)
        );

        $logEntry = $this->getLastLogEntry();

        $this->assertSame(LogLevel::INFO, $logEntry['level']);
        $this->assertSame('Composite info message', $logEntry['message']);
    }

    /**
     * @throws LoggerException
     */
    public function testNoticeLogToBothFileAndDatabase(): void
    {
        $fileLogger = new FileLogger($this->testLogFile);
        $databaseLogger = new DatabaseLogger($this->pdo, 'test_logs');
        $compositeLogger = new CompositeLogger([$fileLogger]);
        $compositeLogger->addLogger($databaseLogger);

        $compositeLogger->notice('Composite notice message');

        $this->assertStringContainsString(
            LogLevel::NOTICE . ': Composite notice message',
            file_get_contents($this->testLogFile)
        );

        $logEntry = $this->getLastLogEntry();

        $this->assertSame(LogLevel::NOTICE, $logEntry['level']);
        $this->assertSame('Composite notice message', $logEntry['message']);
    }

    /**
     * @throws LoggerException
     */
    public function testWarningLogToBothFileAndDatabase(): void
    {
        $fileLogger = new FileLogger($this->testLogFile);
        $databaseLogger = new DatabaseLogger($this->pdo, 'test_logs');
        $compositeLogger = new CompositeLogger([$fileLogger]);
        $compositeLogger->addLogger($databaseLogger);

        $compositeLogger->warning('Composite warning message');

        $this->assertStringContainsString(
            LogLevel::WARNING . ': Composite warning message',
            file_get_contents($this->testLogFile)
        );

        $logEntry = $this->getLastLogEntry();

        $this->assertSame(LogLevel::WARNING, $logEntry['level']);
        $this->assertSame('Composite warning message', $logEntry['message']);
    }

    /**
     * @throws LoggerException
     */
    public function testErrorLogToBothFileAndDatabase(): void
    {
        $fileLogger = new FileLogger($this->testLogFile);
        $databaseLogger = new DatabaseLogger($this->pdo, 'test_logs');
        $compositeLogger = new CompositeLogger([$fileLogger]);
        $compositeLogger->addLogger($databaseLogger);

        $compositeLogger->error('Composite error message');

        $this->assertStringContainsString(
            LogLevel::ERROR . ': Composite error message',
            file_get_contents($this->testLogFile)
        );

        $logEntry = $this->getLastLogEntry();

        $this->assertSame(LogLevel::ERROR, $logEntry['level']);
        $this->assertSame('Composite error message', $logEntry['message']);
    }

    /**
     * @throws LoggerException
     */
    public function testCriticalLogToBothFileAndDatabase(): void
    {
        $fileLogger = new FileLogger($this->testLogFile);
        $databaseLogger = new DatabaseLogger($this->pdo, 'test_logs');
        $compositeLogger = new CompositeLogger([$fileLogger]);
        $compositeLogger->addLogger($databaseLogger);

        $compositeLogger->critical('Composite critical message');

        $this->assertStringContainsString(
            LogLevel::CRITICAL . ': Composite critical message',
            file_get_contents($this->testLogFile)
        );

        $logEntry = $this->getLastLogEntry();

        $this->assertSame(LogLevel::CRITICAL, $logEntry['level']);
        $this->assertSame('Composite critical message', $logEntry['message']);
    }

    /**
     * @throws LoggerException
     */
    public function testAlertLogToBothFileAndDatabase(): void
    {
        $fileLogger = new FileLogger($this->testLogFile);
        $databaseLogger = new DatabaseLogger($this->pdo, 'test_logs');
        $compositeLogger = new CompositeLogger([$fileLogger]);
        $compositeLogger->addLogger($databaseLogger);

        $compositeLogger->alert('Composite alert message');

        $this->assertStringContainsString(
            LogLevel::ALERT . ': Composite alert message',
            file_get_contents($this->testLogFile)
        );

        $logEntry = $this->getLastLogEntry();

        $this->assertSame(LogLevel::ALERT, $logEntry['level']);
        $this->assertSame('Composite alert message', $logEntry['message']);
    }

    /**
     * @throws LoggerException
     */
    public function testEmergencyLogToBothFileAndDatabase(): void
    {
        $fileLogger = new FileLogger($this->testLogFile);
        $databaseLogger = new DatabaseLogger($this->pdo, 'test_logs');
        $compositeLogger = new CompositeLogger([$fileLogger]);
        $compositeLogger->addLogger($databaseLogger);

        $compositeLogger->emergency('Composite emergency message');

        $this->assertStringContainsString(
            LogLevel::EMERGENCY . ': Composite emergency message',
            file_get_contents($this->testLogFile)
        );

        $logEntry = $this->getLastLogEntry();

        $this->assertSame(LogLevel::EMERGENCY, $logEntry['level']);
        $this->assertSame('Composite emergency message', $logEntry['message']);
    }

    protected function setUp(): void
    {
        $this->testLogFile = sys_get_temp_dir() . '/test_log_file.log';
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
        if (file_exists($this->testLogFile)) {
            unlink($this->testLogFile);
        }
        $this->pdo->exec('DROP TABLE IF EXISTS test_logs');
    }
}
