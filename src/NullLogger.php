<?php

namespace Tnapf\Logger;

use Psr\Log\AbstractLogger;
use Stringable;

class NullLogger extends AbstractLogger
{
    public function log(mixed $level, string|Stringable $message, array $context = []): void
    {
        // No action taken
    }
}
