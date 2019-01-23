<?php
declare(strict_types=1);

namespace Granam\DiceRolls\Templates\Rollers;

use Granam\DiceRolls\Templates\Dices\Dice1d6;
use Granam\DiceRolls\Templates\Evaluators\FourOrMoreAsOneZeroOtherwiseEvaluator;
use Granam\DiceRolls\Templates\RollOn\NoRollOn;
use Granam\DiceRolls\Templates\RollOn\RollOn4Plus;
use Granam\Tests\DiceRolls\Templates\Rollers\AbstractRollerTest;
use Granam\Integer\IntegerInterface;

class Roller1d6DrdPlusBonusTest extends AbstractRollerTest
{

    /**
     * @test
     */
    public function I_can_create_it()
    {
        $roller1d6DrdPlusBonus = Roller1d6DrdPlusBonus::getIt();
        self::assertSame($roller1d6DrdPlusBonus, Roller1d6DrdPlusBonus::getIt());
        self::assertInstanceOf(Dice1d6::class, $roller1d6DrdPlusBonus->getDice());
        self::assertInstanceOf(IntegerInterface::class, $roller1d6DrdPlusBonus->getNumberOfStandardRolls());
        self::assertSame(1, $roller1d6DrdPlusBonus->getNumberOfStandardRolls()->getValue());
        self::assertInstanceOf(FourOrMoreAsOneZeroOtherwiseEvaluator::class, $roller1d6DrdPlusBonus->getDiceRollEvaluator());
        self::assertInstanceOf(RollOn4Plus::class, $roller1d6DrdPlusBonus->getBonusRollOn());
        self::assertInstanceOf(NoRollOn::class, $roller1d6DrdPlusBonus->getMalusRollOn());
    }

    /**
     * @test
     */
    public function I_can_roll_by_it()
    {
        $roller1d6DrdPlusBonus = Roller1d6DrdPlusBonus::getIt();
        $previousRoll = null;
        for ($attempt = 1; $attempt < self::MAX_ROLL_ATTEMPTS; $attempt++) {
            $roll = $roller1d6DrdPlusBonus->roll();
            self::assertNotSame($previousRoll, $roll);
            self::assertGreaterThanOrEqual(0, $roll->getValue());
            self::assertCount(1, $roll->getStandardDiceRolls());
            self::assertCount(0, $roll->getMalusDiceRolls());
            if (count($roll->getBonusDiceRolls()) > 2) { // at least 2 positive bonus rolls (+ last negative bonus roll)
                self::assertEquals(
                    count($roll->getDiceRolls()) - 1, // last bonus roll does not trigger bonus value (< 4)
                    $roll->getValue()
                );
                break; // at least two bonus rolls in a row happened
            }
            $previousRoll = $roll;
        }
        self::assertLessThan(self::MAX_ROLL_ATTEMPTS, $attempt, 'Expected at least two bonuses in a row');
        self::assertEquals(new Roller1d6DrdPlusBonus(), $roller1d6DrdPlusBonus, 'Roller has to be stateless');
    }
}