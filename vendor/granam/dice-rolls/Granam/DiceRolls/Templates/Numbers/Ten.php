<?php
declare(strict_types=1);

namespace Granam\DiceRolls\Templates\Numbers;

class Ten extends Number
{
    /**
     * @return Number|Ten
     */
    public static function getIt(): Ten
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return self::getInstance(10);
    }
}