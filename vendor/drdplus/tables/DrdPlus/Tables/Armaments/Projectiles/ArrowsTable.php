<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Armaments\Projectiles;

use DrdPlus\Tables\Armaments\Projectiles\Partials\ProjectilesTable;

/**
 * See PPH page 88 right column, @link https://pph.drdplus.info/#tabulka_strelnych_a_vrhacich_zbrani
 */
class ArrowsTable extends ProjectilesTable
{
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/arrows.csv';
    }

}