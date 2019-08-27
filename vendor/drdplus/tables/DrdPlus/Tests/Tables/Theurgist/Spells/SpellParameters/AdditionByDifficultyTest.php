<?php declare(strict_types = 1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Tables\Theurgist\Spells\SpellParameters\AdditionByDifficulty;
use Granam\Tests\Tools\TestWithMockery;

class AdditionByDifficultyTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_create_it_with_just_an_addition()
    {
        $additionByDifficulty = new AdditionByDifficulty('123');
        self::assertSame(123, $additionByDifficulty->getAdditionStep());
        self::assertSame(1, $additionByDifficulty->getDifficultyPerAdditionStep());
        self::assertSame(0, $additionByDifficulty->getCurrentAddition());
        self::assertSame(0, $additionByDifficulty->getCurrentDifficultyIncrement());
        self::assertSame('0 {1=>123}', (string)$additionByDifficulty);

        $sameAdditionByDifficulty = new AdditionByDifficulty('1=123');
        self::assertSame(123, $sameAdditionByDifficulty->getAdditionStep());
        self::assertSame(1, $sameAdditionByDifficulty->getDifficultyPerAdditionStep());
        self::assertSame(0, $sameAdditionByDifficulty->getCurrentAddition());
        self::assertSame(0, $sameAdditionByDifficulty->getCurrentDifficultyIncrement());
        self::assertSame('0 {1=>123}', (string)$additionByDifficulty);
    }

    /**
     * @test
     */
    public function I_can_create_it_with_explicit_difficulty_price()
    {
        $additionByDifficulty = new AdditionByDifficulty('456=789');
        self::assertSame(789, $additionByDifficulty->getAdditionStep());
        self::assertSame(456, $additionByDifficulty->getDifficultyPerAdditionStep());
        self::assertSame(0, $additionByDifficulty->getCurrentAddition());
        self::assertSame(0, $additionByDifficulty->getCurrentDifficultyIncrement());
        self::assertSame('0 {456=>789}', (string)$additionByDifficulty);
    }

    /**
     * @test
     */
    public function I_can_create_it_with_custom_current_addition()
    {
        $additionByDifficulty = new AdditionByDifficulty('2=3', 7);
        self::assertSame(3, $additionByDifficulty->getAdditionStep());
        self::assertSame(2, $additionByDifficulty->getDifficultyPerAdditionStep());
        self::assertSame(7, $additionByDifficulty->getCurrentAddition());
        self::assertSame(5 /* 7 / 3 * 2, round up */, $additionByDifficulty->getCurrentDifficultyIncrement());
        self::assertSame('7 {2=>3}', (string)$additionByDifficulty);
    }

    /**
     * @test
     */
    public function I_can_increase_current_addition()
    {
        $additionByDifficulty = new AdditionByDifficulty(5);
        self::assertSame(5, $additionByDifficulty->getAdditionStep());
        self::assertSame(0, $additionByDifficulty->getCurrentAddition());
        self::assertSame('0 {1=>5}', (string)$additionByDifficulty);
        $same = $additionByDifficulty->add(0);
        self::assertSame($same, $additionByDifficulty);
        $increased = $additionByDifficulty->add(3);
        self::assertSame(0, $additionByDifficulty->getCurrentAddition(), 'Original addition should still has a zero current');
        self::assertNotSame($additionByDifficulty, $increased);
        self::assertSame(3, $increased->getValue());
        self::assertSame('3 {1=>5}', (string)$increased);
    }

    /**
     * @test
     */
    public function I_can_decrease_current_addition(): void
    {
        $additionByDifficulty = new AdditionByDifficulty(5);
        self::assertSame(5, $additionByDifficulty->getAdditionStep());
        self::assertSame(0, $additionByDifficulty->getCurrentAddition());
        self::assertSame('0 {1=>5}', (string)$additionByDifficulty);
        $same = $additionByDifficulty->sub(0);
        self::assertSame($same, $additionByDifficulty);
        $increased = $additionByDifficulty->sub(7);
        self::assertSame(0, $additionByDifficulty->getCurrentAddition(), 'Original addition should still has a zero current');
        self::assertNotSame($additionByDifficulty, $increased);
        self::assertSame(-7, $increased->getValue());
        self::assertSame('-7 {1=>5}', (string)$increased);
    }

    /**
     * @test
     */
    public function I_will_get_zero_as_difficulty_increment_of_unchangeable()
    {
        $additionByDifficulty = new AdditionByDifficulty('15=0');
        self::assertSame(0, $additionByDifficulty->getCurrentDifficultyIncrement());
    }

    /**
     * @test
     */
    public function I_can_not_change_it_by_add_when_no_step()
    {
        $this->expectException(\DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\AdditionByDifficultyWithoutStepsCanNotBeChanged::class);
        $this->expectExceptionMessageRegExp('~7~');
        try {
            $additionByDifficulty = new AdditionByDifficulty('1=0');
            $additionByDifficulty->add(0);
        } catch (\Exception $exception) {
            self::fail('No exception expected so far: ' . $exception->getMessage());
        }
        $additionByDifficulty->add(7);
    }

    /**
     * @test
     */
    public function I_can_not_change_it_by_sub_when_no_step()
    {
        $this->expectException(\DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\AdditionByDifficultyWithoutStepsCanNotBeChanged::class);
        $this->expectExceptionMessageRegExp('~9~');
        try {
            $additionByDifficulty = new AdditionByDifficulty('999=0');
            $additionByDifficulty->sub(0);
        } catch (\Exception $exception) {
            self::fail('No exception expected so far: ' . $exception->getMessage());
        }
        $additionByDifficulty->sub(9);
    }

    /**
     * @test
     */
    public function I_can_not_create_it_without_value()
    {
        $this->expectException(\DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByDifficultyNotation::class);
        new AdditionByDifficulty('');
    }

    /**
     * @test
     */
    public function I_can_not_create_it_with_too_many_parts()
    {
        $this->expectException(\DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByDifficultyNotation::class);
        $this->expectExceptionMessageRegExp('~1=2=3~');
        new AdditionByDifficulty('1=2=3');
    }

    /**
     * @test
     */
    public function I_can_not_create_it_with_empty_difficulty_price()
    {
        $this->expectException(\DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByDifficultyNotation::class);
        new AdditionByDifficulty('=2');
    }

    /**
     * @test
     */
    public function I_can_not_create_it_with_invalid_difficulty_price()
    {
        $this->expectException(\DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfDifficultyIncrement::class);
        $this->expectExceptionMessageRegExp('~foo~');
        new AdditionByDifficulty('foo=2');
    }

    /**
     * @test
     */
    public function I_can_not_create_it_with_empty_addition()
    {
        $this->expectException(\DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByDifficultyNotation::class);
        $this->expectExceptionMessageRegExp('~5=~');
        new AdditionByDifficulty('5=');
    }

    /**
     * @test
     */
    public function I_can_not_create_it_with_invalid_addition()
    {
        $this->expectException(\DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByDifficultyValue::class);
        $this->expectExceptionMessageRegExp('~bar~');
        new AdditionByDifficulty('13=bar');
    }
}