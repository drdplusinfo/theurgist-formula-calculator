<?php
declare(strict_types=1);

namespace Granam\DiceRolls\Templates\Numbers;

class One extends Number
{
    /**
     * @return Number|One
     */
    public static function getIt(): One
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return self::getInstance(1);
    }
}