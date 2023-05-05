<?php

namespace Tests\Tnapf\Logger;

use PHPUnit\Framework\TestCase;
use Tnapf\Logger\NullLogger;

class NullLoggerTest extends TestCase
{
    public function testLogMethods(): void
    {
        $logger = new NullLogger();

        $logger->emergency('Emergency message');
        $logger->alert('Alert message');
        $logger->critical('Critical message');
        $logger->error('Error message');
        $logger->warning('Warning message');
        $logger->notice('Notice message');
        $logger->info('Info message');
        $logger->debug('Debug message');

        $context = ['user_id' => 1, 'user_name' => 'John Doe'];
        $logger->emergency('Emergency message', $context);
        $logger->alert('Alert message', $context);
        $logger->critical('Critical message', $context);
        $logger->error('Error message', $context);
        $logger->warning('Warning message', $context);
        $logger->notice('Notice message', $context);
        $logger->info('Info message', $context);
        $logger->debug('Debug message', $context);

        $this->assertTrue(true);
    }
}
