<?php declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Tables\Tables;
use DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameterTest;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\AdditionByDifficulty;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Trap;

class TrapTest extends CastingParameterTest
{

    protected function I_can_create_it_negative(): void
    {
        $trap = new Trap(['-456', '4=6', PropertyCode::INTELLIGENCE], Tables::getIt());
        self::assertSame(-456, $trap->getValue());
        self::assertSame($trap->getPropertyCode(), PropertyCode::getIt(PropertyCode::INTELLIGENCE));
        self::assertEquals(new AdditionByDifficulty('4=6'), $trap->getAdditionByDifficulty());
        self::assertSame('-456 intelligence (0 {4=>6})', (string)$trap);
    }

    protected function I_can_create_it_with_zero(): void
    {
        $trap = new Trap(['0', '78=321', PropertyCode::CHARISMA], Tables::getIt());
        self::assertSame(0, $trap->getValue());
        self::assertSame($trap->getPropertyCode(), PropertyCode::getIt(PropertyCode::CHARISMA));
        self::assertEquals(new AdditionByDifficulty('78=321'), $trap->getAdditionByDifficulty());
        self::assertSame('0 charisma (0 {78=>321})', (string)$trap);
    }

    protected function I_can_create_it_positive(): void
    {
        $trap = new Trap(['35689', '332211', PropertyCode::ENDURANCE], Tables::getIt());
        self::assertSame(35689, $trap->getValue());
        self::assertSame($trap->getPropertyCode(), PropertyCode::getIt(PropertyCode::ENDURANCE));
        self::assertEquals(new AdditionByDifficulty('332211'), $trap->getAdditionByDifficulty());
        self::assertSame('35689 endurance (0 {1=>332211})', (string)$trap);
    }

    protected function I_can_not_change_initial_addition()
    {
        $trap = new Trap(['1', '2', PropertyCode::AGILITY], Tables::getIt());
        self::assertSame(1, $trap->getValue());
        self::assertSame($trap->getPropertyCode(), PropertyCode::getIt(PropertyCode::AGILITY));
        self::assertEquals(new AdditionByDifficulty('2'), $trap->getAdditionByDifficulty());
        self::assertSame('1 agility (0 {1=>2})', (string)$trap);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfPropertyUsedForTrap
     * @expectedExceptionMessageRegExp ~goodness~
     */
    public function I_can_not_create_it_with_unknown_property(): void
    {
        new Trap(['35689', '332211', 'goodness'], Tables::getIt());
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfPropertyUsedForTrap
     * @expectedExceptionMessageRegExp ~nothing~
     */
    public function I_can_not_create_it_without_property(): void
    {
        new Trap(['35689', '332211'], Tables::getIt());
    }

    /**
     * @test
     */
    public function I_can_get_its_clone_changed_by_addition(): void
    {
        $original = new Trap(['123', '456=789', PropertyCode::ENDURANCE], Tables::getIt());
        self::assertSame($original, $original->getWithAddition(0));
        $increased = $original->getWithAddition(456);
        self::assertSame(579, $increased->getValue());
        self::assertSame(456, $increased->getAdditionByDifficulty()->getCurrentAddition());
        self::assertSame($original->getPropertyCode(), $increased->getPropertyCode());
        self::assertNotSame($original, $increased);
        $increasedBySame = $increased->getWithAddition(456);
        self::assertSame($increased, $increasedBySame);

        $decreased = $original->getWithAddition(-579);
        self::assertSame(-456, $decreased->getValue());
        self::assertNotSame($original, $decreased);
        self::assertNotSame($original, $increased);
        self::assertSame($original->getPropertyCode(), $decreased->getPropertyCode());
        self::assertSame(-579, $decreased->getAdditionByDifficulty()->getCurrentAddition());
        $decreasedBySame = $decreased->getWithAddition(-579);
        self::assertSame($decreased, $decreasedBySame);
    }
}