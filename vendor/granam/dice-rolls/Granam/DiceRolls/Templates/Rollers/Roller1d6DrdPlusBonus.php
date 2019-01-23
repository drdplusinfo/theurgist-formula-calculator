<?php
declare(strict_types=1);

namespace Granam\DiceRolls\Templates\Rollers;

use Granam\DiceRolls\Roller;
use Granam\DiceRolls\Templates\Dices\Dice1d6;
use Granam\DiceRolls\Templates\Numbers\One;
use Granam\DiceRolls\Templates\Evaluators\FourOrMoreAsOneZeroOtherwiseEvaluator;
use Granam\DiceRolls\Templates\RollOn\RollOn4Plus;
use Granam\DiceRolls\Templates\RollOn\NoRollOn;

class Roller1d6DrdPlusBonus extends Roller
{
    private static $roller1d6DrdPlusBonus;

    /**
     * @return Roller1d6DrdPlusBonus
     */
    public static function getIt(): Roller1d6DrdPlusBonus
    {
        if (self::$roller1d6DrdPlusBonus === null) {
            self::$roller1d6DrdPlusBonus = new static();
        }

        return self::$roller1d6DrdPlusBonus;
    }

    public function __construct()
    {
        parent::__construct(
            Dice1d6::getIt(),
            One::getIt(), // just a single roll of the dice
            FourOrMoreAsOneZeroOtherwiseEvaluator::getIt(), // rolled value 4+ = +1, 3- = 0
            new RollOn4Plus( // recursion -> 4 => roll again
                $this // in case of bonus the same type of roll happens
            ),
            NoRollOn::getIt() // no malus roll
        );
    }
}