<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\Environment;
use PHPUnit\Framework\TestCase;

class EnvironmentTest extends TestCase
{
    /**
     * @test
     */
    public function I_can_detect_cli_request()
    {
        $environment = new Environment('stone', null, null);
        self::assertFalse($environment->isCliRequest());
        $environment = new Environment('cli', null, null);
        self::assertTrue($environment->isCliRequest());
    }

    /**
     * @test
     */
    public function I_can_get_php_sapi(): void
    {
        $environment = new Environment('stone', null, null);
        self::assertSame('stone', $environment->getPhpSapi());
    }

    /**
     * @test
     */
    public function I_can_control_development_environment_by_env_variable()
    {
        $environmentWithoutProject = new Environment('foo', null, null);
        self::assertFalse($environmentWithoutProject->isOnDevEnvironment(), 'dev environment was not expected');
        $environmentOnStrangeProject = new Environment('foo', 'unknown', null);
        self::assertFalse($environmentOnStrangeProject->isOnDevEnvironment());
        $environmentOnDevProject = new Environment('foo', 'dev', null);
        self::assertTrue($environmentOnDevProject->isOnDevEnvironment());
    }

    /**
     * @test
     */
    public function I_can_detect_localhost()
    {
        $environmentWithoutRemoteAddress = new Environment('foo', null, null);
        self::assertFalse($environmentWithoutRemoteAddress->isOnLocalhost(), 'Localhost should not be detected');
        $environmentWithRemoteAddress = new Environment('foo', null, '999.999.999.999');
        self::assertFalse($environmentWithRemoteAddress->isOnLocalhost());
        $environmentWithLocalAddress = new Environment('foo', null, '127.0.0.1');
        self::assertTrue($environmentWithLocalAddress->isOnLocalhost());
    }
}
