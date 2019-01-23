<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Partials;

use DrdPlus\Tables\Partials\Percents;
use Granam\Integer\IntegerObject;
use PHPUnit\Framework\TestCase;

abstract class PercentsTest extends TestCase
{
    /**
     * @test
     */
    public function I_can_use_it(): void
    {
        $sutClass = self::getSutClass();
        /** @var Percents $percents */
        $percents = new $sutClass(12);
        self::assertSame(12, $percents->getValue());
        $percents = new $sutClass(new IntegerObject(55));
        self::assertSame(55, $percents->getValue());
    }

    /**
     * @return string|Percents
     */
    protected static function getSutClass(): string
    {
        return \preg_replace('~[\\\]Tests(.+)Test$~', '$1', static::class);
    }

    /**
     * @test
     */
    public function I_can_turn_it_into_percents_string(): void
    {
        $sutClass = self::getSutClass();
        /** @var Percents $percents */
        $percents = new $sutClass(56);
        self::assertSame('56 %', (string)$percents);
    }

    /**
     * @test
     */
    public function I_can_get_rate(): void
    {
        $sutClass = self::getSutClass();
        /** @var Percents $percents */
        $percents = new $sutClass(99);
        self::assertSame(0.99, $percents->getRate());
        $percents = new $sutClass(42);
        self::assertSame(0.42, $percents->getRate());
        $percents = new $sutClass(0);
        self::assertSame(0.0, $percents->getRate());
        $percents = new $sutClass(100);
        self::assertSame(1.0, $percents->getRate());
    }

    /**
     * @test
     */
    abstract public function I_can_create_more_than_hundred_of_percents();

    /**
     * @test
     */
    abstract public function I_can_not_create_more_than_hundred_of_percents();

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Partials\Exceptions\UnexpectedPercents
     * @expectedExceptionMessageRegExp ~-1~
     */
    public function I_can_not_create_negative_percents(): void
    {
        $sutClass = self::getSutClass();
        new $sutClass(-1);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Partials\Exceptions\UnexpectedPercents
     * @expectedExceptionMessageRegExp ~half of quarter~
     */
    public function I_can_not_create_it_from_non_integer(): void
    {
        $sutClass = self::getSutClass();
        try {
            new $sutClass(1);
        } catch (\Exception $exception) {
            self::fail('No exception expected so far: ' . $exception->getMessage());
        }
        new $sutClass('half of quarter');
    }

}