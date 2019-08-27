<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Armaments\Partials;

use Granam\String\StringInterface;

interface UnwieldyTable extends HeavyBearablesTable
{
    public const RESTRICTION = 'restriction';

    /**
     * @param string|StringInterface $coverCode
     * @return int
     */
    public function getRestrictionOf($coverCode): int;
}