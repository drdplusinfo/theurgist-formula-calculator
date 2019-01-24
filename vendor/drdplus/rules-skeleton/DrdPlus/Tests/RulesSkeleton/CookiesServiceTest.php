<?php
declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\CookiesService;
use Granam\Tests\Tools\TestWithMockery;

class CookiesServiceTest extends TestWithMockery
{
    /**
     * @test
     * @backupGlobals enabled
     */
    public function I_can_set_get_and_delete_cookie(): void
    {
        $cookiesServiceClass = static::getSutClass();
        /** @var CookiesService $cookiesService */
        $cookiesService = new $cookiesServiceClass();
        self::assertNull($cookiesService->getCookie('foo'));
        self::assertTrue($cookiesService->setCookie('foo', 'bar'));
        self::assertSame('bar', $cookiesService->getCookie('foo'));
        self::assertSame('bar', $_COOKIE['foo'] ?? false);
        self::assertTrue($cookiesService->deleteCookie('foo'));
        self::assertNull($cookiesService->getCookie('foo'));
        self::assertFalse(\array_key_exists('foo', $_COOKIE), 'Cookie should be removed from global array as well');
    }
}