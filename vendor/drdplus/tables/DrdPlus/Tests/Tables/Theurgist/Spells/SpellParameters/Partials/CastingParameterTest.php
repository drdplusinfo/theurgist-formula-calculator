<?php
declare(strict_types = 1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters\Partials;

use DrdPlus\Tables\Theurgist\Spells\SpellParameters\AdditionByDifficulty;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameter;
use Granam\Tests\Tools\TestWithMockery;

abstract class CastingParameterTest extends TestWithMockery
{
    use CastingParameterSetAdditionTrait;

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\Exceptions\MissingValueForFormulaDifficultyAddition
     * @expectedExceptionMessageRegExp ~123~
     */
    public function I_can_not_create_it_with_invalid_points_to_annotation()
    {
        $reflectionMethod = new \ReflectionMethod(CastingParameter::class, '__construct');
        $reflectionMethod->invoke($this->mockery(CastingParameter::class), [123]);
    }

    /**
     * @test
     */
    public function I_can_create_it()
    {
        $this->I_can_create_it_negative();
        $this->I_can_create_it_with_zero();
        $this->I_can_create_it_positive();
    }

    protected function I_can_create_it_negative()
    {
        $sutClass = self::getSutClass();
        /** @var CastingParameter $sut */
        $sut = new $sutClass(['-456', '4=6']);
        self::assertSame(-456, $sut->getValue());
        self::assertEquals(new AdditionByDifficulty('4=6'), $sut->getAdditionByDifficulty());
        self::assertSame('-456 (' . $sut->getAdditionByDifficulty() . ')', (string)$sut);
    }

    protected function I_can_create_it_with_zero()
    {
        $sutClass = self::getSutClass();
        /** @var CastingParameter $sut */
        $sut = new $sutClass(['0', '78=321']);
        self::assertSame(0, $sut->getValue());
        self::assertEquals(new AdditionByDifficulty('78=321'), $sut->getAdditionByDifficulty());
        self::assertSame('0 (' . $sut->getAdditionByDifficulty() . ')', (string)$sut);
    }

    protected function I_can_create_it_positive()
    {
        $sutClass = self::getSutClass();
        /** @var CastingParameter $sut */
        $sut = new $sutClass(['35689', '332211']);
        self::assertSame(35689, $sut->getValue());
        self::assertEquals(new AdditionByDifficulty('332211'), $sut->getAdditionByDifficulty());
        self::assertSame('35689 (' . $sut->getAdditionByDifficulty() . ')', (string)$sut);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\Exceptions\InvalidValueForCastingParameter
     * @expectedExceptionMessageRegExp ~infinite~
     */
    public function I_can_not_create_it_non_numeric()
    {
        $sutClass = self::getSutClass();
        new $sutClass(['infinite', '332211']);
    }

    /**
     * @test
     */
    public function I_can_get_its_clone_changed_by_addition()
    {
        $sutClass = self::getSutClass();
        /** @var CastingParameter $original */
        $original = new $sutClass(['123', '456=789']);
        self::assertSame($original, $original->getWithAddition(0));
        $increased = $original->getWithAddition(456);
        self::assertSame(579, $increased->getValue());
        self::assertSame($original->getAdditionByDifficulty()->getNotation(), $increased->getAdditionByDifficulty()->getNotation());
        self::assertSame(456, $increased->getAdditionByDifficulty()->getCurrentAddition());
        self::assertNotSame($original, $increased);

        $zeroed = $original->getWithAddition(-123);
        self::assertSame(0, $zeroed->getValue());
        self::assertNotSame($original, $zeroed);
        self::assertNotSame($original, $increased);
        self::assertSame(-123, $zeroed->getAdditionByDifficulty()->getCurrentAddition());

        $decreased = $original->getWithAddition(-999);
        self::assertSame(-876, $decreased->getValue());
        self::assertSame($original->getAdditionByDifficulty()->getNotation(), $increased->getAdditionByDifficulty()->getNotation());
        self::assertSame(-999, $decreased->getAdditionByDifficulty()->getCurrentAddition());
        self::assertNotSame($original, $decreased);
    }

}