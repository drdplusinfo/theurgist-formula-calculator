<?php declare(strict_types=1);

namespace Granam\DiceRolls\Templates\Rollers;

use Granam\DiceRolls\DiceRoll;
use Granam\DiceRolls\Roll;
use Granam\DiceRolls\Roller;
use Granam\DiceRolls\Templates\Numbers\One;
use Granam\DiceRolls\Templates\Dices\Dice1d6;
use Granam\DiceRolls\Templates\Evaluators\OneToOneEvaluator;
use Granam\DiceRolls\Templates\RollOn\NoRollOn;
use Granam\DiceRolls\Templates\Rolls\Roll1d6;

class Roller1d6 extends Roller
{
    private static $roller1d6;

    /**
     * @return Roller1d6
     */
    public static function getIt(): Roller1d6
    {
        if (self::$roller1d6 === null) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            self::$roller1d6 = new static();
        }

        return self::$roller1d6;
    }

    public function __construct()
    {
        parent::__construct(
            Dice1d6::getIt(),
            One::getIt(),
            OneToOneEvaluator::getIt(),
            NoRollOn::getIt(),
            NoRollOn::getIt()
        );
    }

    /**
     * @param array|DiceRoll[] $standardDiceRolls
     * @param array|DiceRoll[] $bonusDiceRolls
     * @param array|DiceRoll[] $malusDiceRolls
     * @return Roll1d6|Roll
     */
    protected function createRoll(array $standardDiceRolls, array $bonusDiceRolls, array $malusDiceRolls): Roll
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Roll1d6(array_shift($standardDiceRolls));
    }
}