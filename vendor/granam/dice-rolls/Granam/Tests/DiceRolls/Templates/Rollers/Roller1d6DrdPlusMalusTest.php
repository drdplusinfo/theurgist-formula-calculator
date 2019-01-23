<?php
declare(strict_types=1);

namespace Granam\Tests\DiceRolls\Templates\Rollers;

use Granam\DiceRolls\Templates\Dices\Dice1d6;
use Granam\DiceRolls\Templates\Evaluators\ThreeOrLessAsMinusOneZeroOtherwiseEvaluator;
use Granam\DiceRolls\Templates\Rollers\Roller1d6DrdPlusMalus;
use Granam\DiceRolls\Templates\RollOn\NoRollOn;
use Granam\DiceRolls\Templates\RollOn\RollOn3Minus;
use Granam\Integer\IntegerInterface;

class Roller1d6DrdPlusMalusTest extends AbstractRollerTest
{

    /**
     * @test
     */
    public function I_can_create_it()
    {
        $roller1d6DrdPlusMalus = Roller1d6DrdPlusMalus::getIt();
        self::assertSame($roller1d6DrdPlusMalus, Roller1d6DrdPlusMalus::getIt());
        self::assertInstanceOf(Dice1d6::class, $roller1d6DrdPlusMalus->getDice());
        self::assertInstanceOf(IntegerInterface::class, $roller1d6DrdPlusMalus->getNumberOfStandardRolls());
        self::assertSame(1, $roller1d6DrdPlusMalus->getNumberOfStandardRolls()->getValue());
        self::assertInstanceOf(ThreeOrLessAsMinusOneZeroOtherwiseEvaluator::class, $roller1d6DrdPlusMalus->getDiceRollEvaluator());
        self::assertInstanceOf(NoRollOn::class, $roller1d6DrdPlusMalus->getBonusRollOn());
        self::assertInstanceOf(RollOn3Minus::class, $roller1d6DrdPlusMalus->getMalusRollOn());
    }

    /**
     * @test
     */
    public function I_can_roll_by_it()
    {
        $roller1d6PlusMalus = Roller1d6DrdPlusMalus::getIt();
        $previousRoll = null;
        for ($attempt = 1; $attempt < self::MAX_ROLL_ATTEMPTS; $attempt++) {
            $roll = $roller1d6PlusMalus->roll();
            self::assertNotSame($previousRoll, $roll);
            self::assertLessThanOrEqual(
                $roller1d6PlusMalus->getDice()->getMaximum()->getValue(),
                $roll->getValue()
            );
            self::assertCount(1, $roll->getStandardDiceRolls());
            self::assertCount(0, $roll->getBonusDiceRolls());
            self::assertLessThanOrEqual(3, $roll->getValue());
            if (count($roll->getMalusDiceRolls()) > 2) { // at least 2 positive malus rolls (+ last negative malus)
                self::assertSame(-1 * (count($roll->getDiceRolls()) - 1), $roll->getValue());
                break; // at least two malus rolls in a row happened
            }
            $previousRoll = $roll;
        }
        self::assertLessThan(self::MAX_ROLL_ATTEMPTS, $attempt, 'Expected at least two maluses in a row');
        self::assertEquals(new Roller1d6DrdPlusMalus(), $roller1d6PlusMalus, 'Roller has to be stateless');
    }
}