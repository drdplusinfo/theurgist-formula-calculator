<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\CalculatorSkeleton\History;
use DrdPlus\RulesSkeleton\CookiesService;
use DrdPlus\Tests\CalculatorSkeleton\Partials\AbstractCalculatorContentTest;

/**
 * @backupGlobals enabled
 */
class HistoryTest extends AbstractCalculatorContentTest
{
    /** @var CookiesService */
    private $cookiesService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cookiesService = new CookiesService();
    }

    /**
     * @test
     */
    public function Values_from_url_get_are_ignored(): void
    {
        $history = new History(
            $this->cookiesService,
            true, // remove previous history, if any
            ['from' => 'inner memory'],
            true, // remember current values
            'foo'
        );
        self::assertFalse($history->shouldForgotHistory());
        self::assertNull($history->getValue('from'));
        $nextHistory = new History(
            $this->cookiesService,
            false, // do not remove previous history
            [], // no values to remember this time
            true, // remember current values
            'foo'
        );
        self::assertSame('inner memory', $nextHistory->getValue('from')); // taken from previous history life-cycle
        $_GET['from'] = 'get';
        self::assertNull($history->getValue('from'), 'Should not be affected by any change');
        self::assertSame('inner memory', $nextHistory->getValue('from'), 'Should not be affected by current GET values');
    }

    /**
     * @test
     */
    public function History_is_immediately_forgotten_if_requested(): void
    {
        $bringingFoo = new History($this->cookiesService, true /*remove previous history*/, ['foo' => 'FOO'], true, __FUNCTION__);
        self::assertNull($bringingFoo->getValue('foo'));
        $bringingBar = new History(
            $this->cookiesService,
            false, // do NOT remove previous history
            ['bar' => 'BAR'],
            true, // remember current values
            __FUNCTION__ // cookies prefix
        ); // sets bar
        self::assertSame('FOO', $bringingBar->getValue('foo'), 'Should have value from previous history creation');
        self::assertNull($bringingBar->getValue('bar'), 'Should not have value from current history creation');
        $bringingBaz = new History(
            $this->cookiesService,
            false, // do NOT remove previous history
            ['baz' => 'BAZ'],
            true, // remember current values
            __FUNCTION__ // cookies prefix
        ); // sets baz
        self::assertSame('FOO', $bringingBar->getValue('foo'), 'Existing instances should NOT be affected');
        self::assertNull($bringingBaz->getValue('foo'));
        self::assertSame('BAR', $bringingBaz->getValue('bar'));
    }

    /**
     * @test
     */
    public function History_is_truncated_when_current_values_are_empty_only_if_cookie_history_expires(): void
    {
        $bringingFoo = new History(
            $this->cookiesService,
            true, // remove previous history, if any
            ['foo' => 'FOO'],
            true, // remember current values
            __FUNCTION__ // cookies prefix
        );
        self::assertNull($bringingFoo->getValue('foo'));
        $bringingBar = new History(
            $this->cookiesService,
            false, // do NOT remove previous history
            ['bar' => 'BAR'],
            true, // remember current values
            __FUNCTION__, // cookies prefix
            -1 // TTL
        );
        self::assertNull($bringingFoo->getValue('foo'), 'Existing instances should NOT be affected');
        self::assertSame('FOO', $bringingBar->getValue('foo'));
        self::assertNull($bringingBar->getValue('bar'));
        $anotherHistory = new History(
            $this->cookiesService,
            false, // do NOT remove previous history
            [], // empty values
            true, // remember current values
            __FUNCTION__ // cookies prefix
        );
        self::assertSame('FOO', $bringingBar->getValue('foo'));
        self::assertNull($bringingBar->getValue('bar'));
        self::assertNull($anotherHistory->getValue('foo'));
        self::assertSame('BAR', $anotherHistory->getValue('bar'), 'Nothing should changed with empty current values');
        $_COOKIE['configurator_history_token-' . __FUNCTION__] = false;
        $yetAnotherHistory = new History(
            $this->cookiesService,
            false, // do NOT remove previous history
            [], // empty values
            false, // do NOT remember current values
            __FUNCTION__ // cookies prefix
        );
        self::assertNull($anotherHistory->getValue('foo'));
        self::assertNull($yetAnotherHistory->getValue('bar'));
    }
}
