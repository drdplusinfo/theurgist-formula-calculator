<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Armaments\Projectiles;

use DrdPlus\Tables\Armaments\Projectiles\Partials\ProjectilesTable;

/**
 * See PPH page 88 right column, @link https://pph.drdplus.info/#tabulka_strelnych_a_vrhacich_zbrani
 */
class SlingStonesTable extends ProjectilesTable
{
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/sling_stones.csv';
    }

}