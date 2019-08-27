<?php declare(strict_types=1);

namespace Granam\Tests\DiceRolls\Templates\Rollers;

use Granam\DiceRolls\Templates\Dices\Dice1d6;
use Granam\DiceRolls\Templates\Evaluators\FourOrMoreAsOneZeroOtherwiseEvaluator;
use Granam\DiceRolls\Templates\Rollers\Roller1d6YesOrNo;
use Granam\DiceRolls\Templates\RollOn\NoRollOn;
use Granam\Integer\IntegerInterface;

class Roller1d6YesOrNoTest extends AbstractRollerTest
{
    /**
     * @test
     */
    public function I_can_create_it()
    {
        $roller1d6YesOrNo = Roller1d6YesOrNo::getIt();
        self::assertSame($roller1d6YesOrNo, Roller1d6YesOrNo::getIt());
        self::assertInstanceOf(Dice1d6::class, $roller1d6YesOrNo->getDice());
        self::assertInstanceOf(IntegerInterface::class, $roller1d6YesOrNo->getNumberOfStandardRolls());
        self::assertSame(1, $roller1d6YesOrNo->getNumberOfStandardRolls()->getValue());
        self::assertInstanceOf(FourOrMoreAsOneZeroOtherwiseEvaluator::class, $roller1d6YesOrNo->getDiceRollEvaluator());
        self::assertInstanceOf(NoRollOn::class, $roller1d6YesOrNo->getBonusRollOn());
        self::assertInstanceOf(NoRollOn::class, $roller1d6YesOrNo->getMalusRollOn());
    }

    /**
     * @test
     */
    public function I_can_roll_by_it()
    {
        $roller1d6YesOrNo = Roller1d6YesOrNo::getIt();
        $waitingForValues = [0, 1];
        $attemptsRemain = 1000;
        do {
            $roll = $roller1d6YesOrNo->roll();
            foreach ($waitingForValues as $index => $waitingForValue) {
                if ($waitingForValue === $roll->getValue()) {
                    unset($waitingForValues[$index]);
                    break;
                }
            }
        } while (count($waitingForValues) > 0 && --$attemptsRemain > 0);
        self::assertEmpty($waitingForValues);
    }

}