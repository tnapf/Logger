<?php

namespace Tnapf\Logger;

use Stringable;
use Tnapf\Logger\Exceptions\CouldNotCreateResourceException;
use Tnapf\Logger\Exceptions\CouldNotWriteResourceException;
use Tnapf\Logger\Exceptions\FileOperationException;

class RotatingFileLogger extends FileLogger
{
    /**
     * @param int $maxSizeBytes 10 MB by default
     * @param int $maxAgeSeconds 7 days by default
     * @throws CouldNotCreateResourceException if the log file cannot be created.
     */
    public function __construct(
        string $logFile,
        int $permissions = 0644,
        protected int $maxSizeBytes = 10 * 1024 * 1024,
        protected int $maxAgeSeconds = 7 * 24 * 60 * 60
    ) {
        parent::__construct($logFile, $permissions);
    }

    /**
     * @throws CouldNotWriteResourceException if the log file cannot be created.
     * @throws FileOperationException if the archive file cannot be renamed.
     */
    public function log(mixed $level, string|Stringable $message, array $context = []): void
    {
        $this->rotateLogsIfNeeded();
        parent::log($level, $message, $context);
    }

    /**
     * @throws CouldNotWriteResourceException if the log file cannot be created.
     * @throws FileOperationException if the archive file cannot be renamed.
     */
    protected function rotateLogsIfNeeded(): void
    {
        if ($this->shouldRotateBySize() || $this->shouldRotateByAge()) {
            $this->rotate();
        }
    }

    /**
     * @throws FileOperationException if the file can't be opened or the size cannot be retrieved.
     */
    protected function shouldRotateBySize(): bool
    {
        $fileResource = fopen($this->logFile, 'rb');

        if ($fileResource === false) {
            throw new FileOperationException('Error opening file');
        }

        fseek($fileResource, 0, SEEK_END);
        $fileSize = (int)ftell($fileResource);
        fclose($fileResource);

        return $fileSize > $this->maxSizeBytes;
    }

    /**
     * @throws FileOperationException if the file modification time cannot be retrieved.
     */
    protected function shouldRotateByAge(): bool
    {
        $fileModificationTime = filemtime($this->logFile);

        if ($fileModificationTime === false) {
            throw new FileOperationException('Error getting file modification time');
        }

        $fileAgeInSeconds = time() - $fileModificationTime;
        return $fileAgeInSeconds > $this->maxAgeSeconds;
    }

    /**
     * @throws CouldNotWriteResourceException if the log file cannot be created.
     */
    protected function rotate(): void
    {
        $archivePath = $this->generateArchivePathWithCounter($this->logFile);
        $this->moveLogToArchive($archivePath);
    }

    /**
     * @throws CouldNotWriteResourceException if the log file cannot be created.
     */
    protected function moveLogToArchive(string $archivePath): void
    {
        rename($this->logFile, $archivePath);
        if (!touch($this->logFile)) {
            throw new CouldNotWriteResourceException('Unable to create the log file');
        }
    }


    protected function generateArchivePathWithCounter($path): string
    {
        $counter = 1;
        while (file_exists($path . '.' . $counter)) {
            $counter++;
        }

        return $path . '.' . $counter;
    }
}
