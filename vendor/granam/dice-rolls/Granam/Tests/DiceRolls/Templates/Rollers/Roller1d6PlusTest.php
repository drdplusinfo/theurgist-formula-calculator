<?php declare(strict_types=1);

namespace Granam\Tests\DiceRolls\Templates\Rollers;

use Granam\DiceRolls\Templates\Dices\Dice1d6;
use Granam\DiceRolls\Templates\Evaluators\OneToOneEvaluator;
use Granam\DiceRolls\Templates\Rollers\Roller1d6Plus;
use Granam\DiceRolls\Templates\RollOn\NoRollOn;
use Granam\DiceRolls\Templates\RollOn\RollOn6;
use Granam\Integer\IntegerInterface;

class Roller1d6PlusTest extends AbstractRollerTest
{

    /**
     * @test
     */
    public function I_can_create_it()
    {
        $roller1d6Plus = Roller1d6Plus::getIt();
        self::assertSame($roller1d6Plus, Roller1d6Plus::getIt());
        self::assertInstanceOf(Dice1d6::class, $roller1d6Plus->getDice());
        self::assertInstanceOf(IntegerInterface::class, $roller1d6Plus->getNumberOfStandardRolls());
        self::assertSame(1, $roller1d6Plus->getNumberOfStandardRolls()->getValue());
        self::assertInstanceOf(OneToOneEvaluator::class, $roller1d6Plus->getDiceRollEvaluator());
        self::assertInstanceOf(RollOn6::class, $roller1d6Plus->getBonusRollOn());
        self::assertInstanceOf(NoRollOn::class, $roller1d6Plus->getMalusRollOn());
    }

    /**
     * @test
     */
    public function I_can_roll_by_it()
    {
        $roller1d6Plus = Roller1d6Plus::getIt();
        $previousRoll = null;
        $roll = null;
        for ($attempt = 1; $attempt < self::MAX_ROLL_ATTEMPTS; $attempt++) {
            $roll = $roller1d6Plus->roll();
            self::assertNotSame($previousRoll, $roll);
            $diceMinimum = $roller1d6Plus->getDice()->getMinimum()->getValue();
            self::assertGreaterThanOrEqual($diceMinimum, $roll->getValue());
            self::assertCount(0, $roll->getMalusDiceRolls());
            if (count($roll->getBonusDiceRolls()) > 2) { // at least 2 positive bonus rolls happens (+ last negative)
                self::assertGreaterThanOrEqual(
                    $this->summarizeDiceRolls($roll->getStandardDiceRolls()) + (count($roll->getBonusDiceRolls()) * $diceMinimum),
                    $roll->getValue()
                );
                break;
            }
            $previousRoll = $roll;
        }
        self::assertLessThan(self::MAX_ROLL_ATTEMPTS, $attempt, 'Expected at least two bonuses in a row');
        self::assertEquals(new Roller1d6Plus(), $roller1d6Plus, 'Roller has to be stateless');
    }
}