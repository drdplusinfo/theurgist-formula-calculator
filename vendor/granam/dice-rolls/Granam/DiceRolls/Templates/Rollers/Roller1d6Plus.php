<?php declare(strict_types=1);

namespace Granam\DiceRolls\Templates\Rollers;

use Granam\DiceRolls\Roller;
use Granam\DiceRolls\Templates\Numbers\One;
use Granam\DiceRolls\Templates\Dices\Dice1d6;
use Granam\DiceRolls\Templates\Evaluators\OneToOneEvaluator;
use Granam\DiceRolls\Templates\RollOn\RollOn6;
use Granam\DiceRolls\Templates\RollOn\NoRollOn;

class Roller1d6Plus extends Roller
{
    /**
     * @var Roller1d6Plus
     */
    private static $roller1d6Plus;

    /**
     * @return Roller1d6Plus
     * @throws \Granam\DiceRolls\Exceptions\InvalidDiceRange
     * @throws \Granam\DiceRolls\Exceptions\InvalidNumberOfRolls
     * @throws \Granam\DiceRolls\Exceptions\BonusAndMalusChanceConflict
     */
    public static function getIt(): Roller1d6Plus
    {
        if (self::$roller1d6Plus === null) {
            self::$roller1d6Plus = new static();
        }

        return self::$roller1d6Plus;
    }

    public function __construct()
    {
        parent::__construct(
            Dice1d6::getIt(),
            One::getIt(),
            OneToOneEvaluator::getIt(),
            new RollOn6($this), // on 6 roll again
            NoRollOn::getIt() // no malus
        );
    }
}