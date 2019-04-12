<?php
declare(strict_types = 1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Tables\Theurgist\Spells\SpellParameters\DifficultyAddition;
use Granam\Tests\Tools\TestWithMockery;

class DifficultyAdditionTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_create_it_with_just_an_addition(): void
    {
        $additionByRealms = new DifficultyAddition('123', 0);
        self::assertSame(123, $additionByRealms->getDifficultyAdditionPerStep());
        self::assertSame(1, $additionByRealms->getRealmsChangePerAdditionStep());
        self::assertSame(0, $additionByRealms->getCurrentAddition());
        self::assertSame('0 {1=>123}', (string)$additionByRealms);

        $sameAdditionByRealms = new DifficultyAddition('1=123', 0);
        self::assertSame(123, $sameAdditionByRealms->getDifficultyAdditionPerStep());
        self::assertSame(1, $sameAdditionByRealms->getRealmsChangePerAdditionStep());
        self::assertSame(0, $sameAdditionByRealms->getCurrentAddition());
        self::assertSame('0 {1=>123}', (string)$additionByRealms);
    }

    /**
     * @test
     */
    public function I_can_create_it_with_realms_price(): void
    {
        $additionByRealms = new DifficultyAddition('456=789', 0);
        self::assertSame(789, $additionByRealms->getDifficultyAdditionPerStep());
        self::assertSame(456, $additionByRealms->getRealmsChangePerAdditionStep());
        self::assertSame(0, $additionByRealms->getCurrentAddition());
        self::assertSame('0 {456=>789}', (string)$additionByRealms);
    }

    /**
     * @test
     */
    public function I_can_create_it_with_custom_current_addition(): void
    {
        $additionByRealms = new DifficultyAddition('2=3', 7);
        self::assertSame(3, $additionByRealms->getDifficultyAdditionPerStep());
        self::assertSame(2, $additionByRealms->getRealmsChangePerAdditionStep());
        self::assertSame(7, $additionByRealms->getCurrentAddition());
        self::assertSame('7 {2=>3}', (string)$additionByRealms);
    }

    /**
     * @test
     */
    public function I_can_increase_current_addition(): void
    {
        $additionByRealms = new DifficultyAddition(5, 0);
        self::assertSame(5, $additionByRealms->getDifficultyAdditionPerStep());
        self::assertSame(0, $additionByRealms->getCurrentAddition());
        self::assertSame('0 {1=>5}', (string)$additionByRealms);
        $same = $additionByRealms->add(0);
        self::assertSame($same, $additionByRealms);
        $increased = $additionByRealms->add(3);
        self::assertSame(0, $additionByRealms->getCurrentAddition(), 'Original addition should still has a zero current');
        self::assertNotSame($additionByRealms, $increased);
        self::assertSame(3, $increased->getValue());
        self::assertSame('3 {1=>5}', (string)$increased);
    }

    /**
     * @test
     */
    public function I_can_decrease_current_addition()
    {
        $additionByRealms = new DifficultyAddition(5, 0);
        self::assertSame(5, $additionByRealms->getDifficultyAdditionPerStep());
        self::assertSame(0, $additionByRealms->getCurrentAddition());
        self::assertSame('0 {1=>5}', (string)$additionByRealms);
        $same = $additionByRealms->sub(0);
        self::assertSame($same, $additionByRealms);
        $increased = $additionByRealms->sub(7);
        self::assertSame(0, $additionByRealms->getCurrentAddition(), 'Original addition should still has a zero current');
        self::assertNotSame($additionByRealms, $increased);
        self::assertSame(-7, $increased->getValue());
        self::assertSame('-7 {1=>5}', (string)$increased);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByRealmsNotation
     */
    public function I_can_not_create_it_without_value()
    {
        new DifficultyAddition('', 0);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByRealmsNotation
     * @expectedExceptionMessageRegExp ~1=2=3~
     */
    public function I_can_not_create_it_with_too_many_parts()
    {
        new DifficultyAddition('1=2=3', 0);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByRealmsNotation
     */
    public function I_can_not_create_it_with_empty_realm_price()
    {
        new DifficultyAddition('=2', 0);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfRealmsIncrement
     * @expectedExceptionMessageRegExp ~foo~
     */
    public function I_can_not_create_it_with_invalid_realm_price()
    {
        new DifficultyAddition('foo=2', 0);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByRealmsNotation
     * @expectedExceptionMessageRegExp ~5=~
     */
    public function I_can_not_create_it_with_empty_addition()
    {
        new DifficultyAddition('5=', 0);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByRealmsValue
     * @expectedExceptionMessageRegExp ~bar~
     */
    public function I_can_not_create_it_with_invalid_addition()
    {
        new DifficultyAddition('13=bar', 0);
    }
}