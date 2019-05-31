<?php
namespace DrdPlus\RulesSkeleton;

use Granam\Strict\Object\StrictObject;

class Environment extends StrictObject
{
    public function isCliRequest(): bool
    {
        return \PHP_SAPI === 'cli';
    }

    public function isOnDevEnvironment(): bool
    {
        return ($_ENV['PROJECT_ENVIRONMENT'] ?? null) === 'dev';
    }

    public function isOnLocalhost(): bool
    {
        return ($_SERVER['REMOTE_ADDR'] ?? null) === '127.0.0.1';
    }
}