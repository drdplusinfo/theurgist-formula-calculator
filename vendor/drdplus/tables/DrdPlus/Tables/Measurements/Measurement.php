<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Measurements;

use Granam\Number\NumberInterface;

interface Measurement extends NumberInterface
{

    /**
     * @return string
     */
    public function getUnit(): string;

    /**
     * @return array|string[]
     */
    public function getPossibleUnits(): array;

}