<?php declare(strict_types=1);

namespace Granam\Tests\WebContentBuilder;

use Granam\WebContentBuilder\CookiesBag;
use Granam\Tests\Tools\TestWithMockery;

class CookiesBagTest extends TestWithMockery
{
    /**
     * @test
     * @backupGlobals enabled
     */
    public function I_can_set_get_and_delete_cookie(): void
    {
        $cookiesBagClass = static::getSutClass();
        /** @var CookiesBag $cookiesBag */
        $cookiesBag = new $cookiesBagClass();
        self::assertNull($cookiesBag->getCookie('foo'));
        self::assertTrue($cookiesBag->setCookie('foo', 'bar'));
        self::assertSame('bar', $cookiesBag->getCookie('foo'));
        self::assertSame('bar', $_COOKIE['foo'] ?? false);
        self::assertTrue($cookiesBag->deleteCookie('foo'));
        self::assertNull($cookiesBag->getCookie('foo'));
        self::assertFalse(\array_key_exists('foo', $_COOKIE), 'Cookie should be removed from global array as well');
    }
}