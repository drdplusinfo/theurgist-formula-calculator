<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Armaments\Partials;

use DrdPlus\Tables\Table;
use Granam\String\StringInterface;

interface BearablesTable extends Table
{
    public const WEIGHT = 'weight';

    /**
     * @param string|StringInterface $itemCode
     * @return float
     */
    public function getWeightOf($itemCode): float;
}