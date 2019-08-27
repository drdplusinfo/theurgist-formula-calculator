<?php declare(strict_types=1);

namespace Granam\DiceRolls\Templates\Dices;

use Granam\DiceRolls\Templates\Numbers\One;
use Granam\DiceRolls\Templates\Numbers\Ten;

class Dice1d10 extends CustomDice
{
    private static $dice1d10;

    /**
     * @return Dice1d10
     */
    public static function getIt(): Dice1d10
    {
        if (self::$dice1d10 === null) {
            self::$dice1d10 = new static();
        }

        return self::$dice1d10;
    }

    public function __construct()
    {
        parent::__construct(One::getIt(), Ten::getIt());
    }
}
