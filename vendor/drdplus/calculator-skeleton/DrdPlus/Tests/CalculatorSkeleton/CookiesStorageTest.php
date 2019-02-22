<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\CalculatorSkeleton\CookiesStorage;
use DrdPlus\RulesSkeleton\CookiesService;
use Granam\Tests\Tools\TestWithMockery;

/**
 * @backupGlobals enabled
 */
class CookiesStorageTest extends TestWithMockery
{
    use Partials\CalculatorContentTestTrait;

    /**
     * @test
     */
    public function Values_from_global_request_values_are_ignored(): void
    {
        $cookiesStorage = new CookiesStorage(new CookiesService(), 'foo');
        self::assertNull($cookiesStorage->getValue('from'));
        $cookiesStorage->storeValues(['from' => 'inner memory'], null);
        self::assertSame('inner memory', $cookiesStorage->getValue('from'));
        $_GET['from'] = 'get';
        $_POST['from'] = 'post';
        $_COOKIE['from'] = 'cookie';
        $_REQUEST['from'] = 'request';
        self::assertSame('inner memory', $cookiesStorage->getValue('from'));
    }

    /**
     * @test
     */
    public function Values_can_be_stored_replaced_and_deleted(): void
    {
        $cookiesStorage = new CookiesStorage(new CookiesService(), 'foo');
        self::assertSame([], $cookiesStorage->getValues());
        self::assertNull($cookiesStorage->getValue('bar'));

        $cookiesStorage->storeValues($values = ['bar' => 'baz'], null);
        self::assertSame($values, $cookiesStorage->getValues());
        self::assertSame('baz', $cookiesStorage->getValue('bar'));

        $cookiesStorage->storeValues($newValues = ['qux' => 'FOO'], null);
        self::assertSame($newValues, $cookiesStorage->getValues());
        self::assertNull($cookiesStorage->getValue('bar'));
        self::assertSame('FOO', $cookiesStorage->getValue('qux'));

        $cookiesStorage->deleteAll();
        self::assertSame([], $cookiesStorage->getValues());
        self::assertNull($cookiesStorage->getValue('bar'));
        self::assertNull($cookiesStorage->getValue('qux'));
    }
}
