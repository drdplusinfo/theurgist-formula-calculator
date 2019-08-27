<?php declare(strict_types=1);

namespace Granam\DiceRolls\Templates\Rollers;

use Granam\DiceRolls\Roller;
use Granam\DiceRolls\Templates\Dices\Dice1d6;
use Granam\DiceRolls\Templates\Numbers\One;
use Granam\DiceRolls\Templates\Evaluators\ThreeOrLessAsMinusOneZeroOtherwiseEvaluator;
use Granam\DiceRolls\Templates\RollOn\RollOn3Minus;
use Granam\DiceRolls\Templates\RollOn\NoRollOn;

class Roller1d6DrdPlusMalus extends Roller
{
    private static $roller1d6DrdPlusMalus;

    /**
     * @return Roller1d6DrdPlusMalus
     */
    public static function getIt(): Roller1d6DrdPlusMalus
    {
        if (self::$roller1d6DrdPlusMalus === null) {
            self::$roller1d6DrdPlusMalus = new static();
        }

        return self::$roller1d6DrdPlusMalus;
    }

    public function __construct()
    {
        parent::__construct(
            Dice1d6::getIt(),
            One::getIt(), // just a single roll of the dice
            ThreeOrLessAsMinusOneZeroOtherwiseEvaluator::getIt(), // value of 1-3 is turned into malus -1, higher values to 0
            NoRollOn::getIt(), // no bonus roll
            new RollOn3Minus( // in case of malus (-1) rolling continues, otherwise stops
                $this // in case of bonus the same type of roll happens
            )
        );
    }
}