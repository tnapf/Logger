<?php

namespace Tests\Tnapf\Logger;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Tnapf\Logger\Exceptions\CouldNotWriteResourceException;
use Tnapf\Logger\Exceptions\FileOperationException;
use Tnapf\Logger\RotatingFileLogger;

class RotatingFileLoggerTest extends TestCase
{
    public function testLogRotationBySize()
    {
        $maxSizeBytes = 10;
        $logFile = vfsStream::url('logs/test.log');
        $logger = new RotatingFileLogger($logFile, 0644, $maxSizeBytes);
        $logger->log('info', str_repeat('A', $maxSizeBytes));
        $this->assertFileExists($logFile);

        $logger->log('info', str_repeat('A', 1));
        $rotatedLogFile = $logFile . '.1';
        $this->assertFileExists($rotatedLogFile);
    }

    public function testLogRotationByAge()
    {
        $logFile = vfsStream::url('logs/test.log');
        $logger = new RotatingFileLogger($logFile, 0644, 1024, -1);
        $logger->log('info', 'Test message');
        $this->assertFileExists($logFile);

        $logger->log('info', 'Another test message');
        $rotatedLogFile = $logFile . '.1';
        $this->assertFileExists($rotatedLogFile);
    }

    public function testShouldRotateBySizeFileOperationExceptions()
    {
        $logFile = vfsStream::url('logs/test.log');
        $logger = new RotatingFileLogger($logFile, 0200, 1024, 2);
        $nonExistentFile = '/nonexistentdir/nonexistentfile.log';
        $loggerReflection = new ReflectionClass($logger);

        $shouldRotateBySize = $loggerReflection->getMethod('shouldRotateBySize');

        $logFile = $loggerReflection->getProperty('logFile');
        $logFile->setValue($logger, $nonExistentFile);

        $this->expectException(FileOperationException::class);
        $this->expectExceptionMessage('Error opening file');
        $shouldRotateBySize->invoke($logger);
    }

    public function testShouldRotateByAgeFileOperationExceptions()
    {
        $logFile = vfsStream::url('logs/test.log');
        $logger = new RotatingFileLogger($logFile, 0200, 1024, 2);
        $nonExistentFile = '/nonexistentdir/nonexistentfile.log';
        $loggerReflection = new ReflectionClass($logger);

        $shouldRotateByAge = $loggerReflection->getMethod('shouldRotateByAge');

        $logFile = $loggerReflection->getProperty('logFile');
        $logFile->setValue($logger, $nonExistentFile);

        $this->expectException(FileOperationException::class);
        $this->expectExceptionMessage('Error getting file modification time');
        $shouldRotateByAge->invoke($logger);
    }

    public function testCouldNotWriteResourceException()
    {
        $logFile = vfsStream::url('logs/test.log');
        $logger = new RotatingFileLogger($logFile, 0644, 1024, 10);
        $nonExistentFile = '/nonexistentdir/nonexistentfile.log';
        $loggerReflection = new ReflectionClass($logger);

        $rotate = $loggerReflection->getMethod('rotate');

        $logFile = $loggerReflection->getProperty('logFile');
        $logFile->setValue($logger, $nonExistentFile);

        $this->expectException(CouldNotWriteResourceException::class);
        $this->expectExceptionMessage('Unable to create the log file');
        $rotate->invoke($logger);
    }

    protected function setUp(): void
    {
        vfsStream::setup("logs");
    }
}
