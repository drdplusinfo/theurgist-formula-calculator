<?php
declare(strict_types=1);

namespace Granam\DiceRolls\Templates\Numbers;

class Two extends Number
{
    /**
     * @return Number|Two
     */
    public static function getIt(): Two
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return self::getInstance(2);
    }
}