<?php

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\Environment;
use PHPUnit\Framework\TestCase;

class EnvironmentTest extends TestCase
{
    /**
     * @var Environment
     */
    private $environment;

    protected function setUp()
    {
        $this->environment = new Environment();
    }

    /**
     * @test
     */
    public function I_can_detect_cli_request()
    {
        self::assertSame(php_sapi_name() === 'cli', $this->environment->isCliRequest());
    }

    /**
     * @test
     * @backupGlobals enabled
     */
    public function I_can_control_development_environment_by_env_variable()
    {
        unset($_ENV['PROJECT_ENVIRONMENT']);
        self::assertFalse($this->environment->isOnDevEnvironment(), 'dev environment was not expected');
        $_ENV['PROJECT_ENVIRONMENT'] = true;
        self::assertFalse($this->environment->isOnDevEnvironment());
        $_ENV['PROJECT_ENVIRONMENT'] = 'dev';
        self::assertTrue($this->environment->isOnDevEnvironment());
    }

    /**
     * @test
     * @backupGlobals enabled
     */
    public function I_can_detect_localhost()
    {
        unset($_SERVER['REMOTE_ADDR']);
        self::assertFalse($this->environment->isOnLocalhost(), 'Localhost should not be detected');
        $_SERVER['REMOTE_ADDR'] = '999.999.999.999';
        self::assertFalse($this->environment->isOnLocalhost());
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        self::assertTrue($this->environment->isOnLocalhost());
    }
}
