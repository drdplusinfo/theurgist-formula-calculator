<?php declare(strict_types=1);

namespace DrdPlus\RulesSkeleton;

use Granam\Strict\Object\StrictObject;

class Environment extends StrictObject
{
    /** @var string */
    private $phpSapi;
    /** @var string|null */
    private $projectEnvironment;
    /** @var string|null */
    private $remoteAddress;

    public static function createFromGlobals(): Environment
    {
        return new static(\PHP_SAPI, $_ENV['PROJECT_ENVIRONMENT'] ?? null, $_SERVER['REMOTE_ADDR'] ?? null);
    }

    public function __construct(string $phpSapi, ?string $projectEnvironment, ?string $remoteAddress)
    {
        $this->phpSapi = $phpSapi;
        $this->projectEnvironment = $projectEnvironment;
        $this->remoteAddress = $remoteAddress;
    }

    public function isCliRequest(): bool
    {
        return $this->getPhpSapi() === 'cli';
    }

    public function getPhpSapi(): string
    {
        return $this->phpSapi;
    }

    public function isOnDevEnvironment(): bool
    {
        return $this->projectEnvironment === 'dev';
    }

    public function isOnLocalhost(): bool
    {
        return $this->remoteAddress === '127.0.0.1';
    }
}