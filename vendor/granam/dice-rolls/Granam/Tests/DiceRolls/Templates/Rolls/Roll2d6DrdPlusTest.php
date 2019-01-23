<?php
declare(strict_types=1);

namespace Granam\Tests\DiceRolls\Templates\Rolls;

use Granam\DiceRolls\DiceRoll;
use Granam\DiceRolls\Roll;
use Granam\DiceRolls\Templates\Dices\Dice1d6;
use Granam\DiceRolls\Templates\Rollers\Roller1d10;
use Granam\DiceRolls\Templates\Rollers\Roller1d4;
use Granam\DiceRolls\Templates\Rollers\Roller2d6DrdPlus;
use Granam\DiceRolls\Templates\Rolls\Roll2d6DrdPlus;
use Granam\Tests\Tools\TestWithMockery;

class Roll2d6DrdPlusTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_use_it_as_standard_roll(): void
    {
        $roll = Roller2d6DrdPlus::getIt()->roll();
        self::assertInstanceOf(
            Roll::class,
            new Roll2d6DrdPlus($roll->getStandardDiceRolls(), $roll->getBonusDiceRolls(), $roll->getMalusDiceRolls())
        );
    }

    /**
     * @test
     * @dataProvider provideRollsWithInvalidDices
     * @expectedException \Granam\DiceRolls\Templates\Rolls\Exceptions\UnexpectedDice
     * @param array $standardDiceRolls
     * @param array $bonusDiceRolls
     * @param array $malusDiceRolls
     */
    public function I_have_to_create_it_with_six_sided_dices(array $standardDiceRolls, array $bonusDiceRolls, array $malusDiceRolls)
    {
        new Roll2d6DrdPlus($standardDiceRolls, $bonusDiceRolls, $malusDiceRolls);
    }

    public function provideRollsWithInvalidDices(): array
    {
        $diceRolls = [];
        $diceRolls[] = [Roller1d4::getIt()->roll()->getStandardDiceRolls(), [], []];
        $diceRolls[] = [
            [$this->createDiceRollWith1d6(), $this->createDiceRollWith1d6()],
            Roller1d10::getIt()->roll()->getStandardDiceRolls(),
            [],
        ];
        $diceRolls[] = [
            [$this->createDiceRollWith1d6(), $this->createDiceRollWith1d6()],
            [],
            Roller1d10::getIt()->roll()->getStandardDiceRolls(),
        ];

        return $diceRolls;
    }

    /**
     * @param int $rolledValue
     * @return \Mockery\MockInterface|DiceRoll
     */
    private function createDiceRollWith1d6($rolledValue = 3)
    {
        $diceRoll = $this->mockery(DiceRoll::class);
        $diceRoll->shouldReceive('getValue')
            ->andReturn($rolledValue);
        $diceRoll->shouldReceive('getDice')
            ->andReturn(Dice1d6::getIt());

        return $diceRoll;
    }

    /**
     * @test
     * @expectedException \Granam\DiceRolls\Templates\Rolls\Exceptions\UnexpectedNumberOfDiceRolls
     */
    public function I_have_to_create_it_with_two_six_side_dices()
    {
        new Roll2d6DrdPlus([$this->createDiceRollWith1d6()], [], []);
    }

    /**
     * @test
     * @expectedException \Granam\DiceRolls\Templates\Rolls\Exceptions\UnexpectedBonus
     */
    public function I_can_not_create_it_with_bonus_roll_if_should_not_happen()
    {
        new Roll2d6DrdPlus([$this->createDiceRollWith1d6(2), $this->createDiceRollWith1d6(3)], [$this->createDiceRollWith1d6()], []);
    }

    /**
     * @test
     * @expectedException \Granam\DiceRolls\Templates\Rolls\Exceptions\MissingBonusDiceRoll
     */
    public function I_can_not_create_it_without_bonus_roll_if_should_happen()
    {
        new Roll2d6DrdPlus([$this->createDiceRollWith1d6(6), $this->createDiceRollWith1d6(6)], [], []);
    }

    /**
     * @test
     * @expectedException \Granam\DiceRolls\Templates\Rolls\Exceptions\UnexpectedMalus
     */
    public function I_can_not_create_it_with_malus_roll_if_should_not_happen()
    {
        new Roll2d6DrdPlus([$this->createDiceRollWith1d6(2), $this->createDiceRollWith1d6(3)], [], [$this->createDiceRollWith1d6()]);
    }

    /**
     * @test
     * @expectedException \Granam\DiceRolls\Templates\Rolls\Exceptions\MissingMalusDiceRoll
     */
    public function I_can_not_create_it_without_malus_roll_if_should_happen()
    {
        new Roll2d6DrdPlus([$this->createDiceRollWith1d6(1), $this->createDiceRollWith1d6(1)], [], []);
    }

}