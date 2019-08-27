<?php declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\CalculatorSkeleton\CookiesStorage;
use DrdPlus\RulesSkeleton\CookiesService;
use DrdPlus\RulesSkeleton\Request;
use DrdPlus\Tests\CalculatorSkeleton\Partials\AbstractCalculatorContentTest;

class CookiesStorageTest extends AbstractCalculatorContentTest
{
    use Partials\CalculatorContentTestTrait;

    /**
     * @test
     */
    public function Values_from_global_request_values_are_ignored(): void
    {
        $cookiesStorage = new CookiesStorage(new CookiesService($this->getRequest()), 'foo');
        self::assertNull($cookiesStorage->getValue('from'));
        $cookiesStorage->storeValues(['from' => 'inner memory'], null);
        self::assertSame('inner memory', $cookiesStorage->getValue('from'));

        $cookiesStorage = new CookiesStorage(
            new CookiesService(
                new Request($this->getBot(), $this->getEnvironment(), ['from' => 'get'], ['from' => 'post'], ['from' => 'cookie'], [])
            ),
            'foo'
        );
        $cookiesStorage->storeValues(['from' => 'inner memory'], null);
        self::assertSame('inner memory', $cookiesStorage->getValue('from'));
    }

    /**
     * @test
     */
    public function Values_can_be_stored_replaced_and_deleted(): void
    {
        $cookiesStorage = new CookiesStorage(new CookiesService($this->getRequest()), 'foo');
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
