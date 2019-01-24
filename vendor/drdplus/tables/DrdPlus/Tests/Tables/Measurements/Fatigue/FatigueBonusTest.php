<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Measurements\Fatigue;

use DrdPlus\Tables\Measurements\Fatigue\FatigueTable;
use DrdPlus\Tables\Measurements\Wounds\WoundsTable;
use DrdPlus\Tables\Partials\AbstractTable;
use DrdPlus\Tests\Tables\Measurements\AbstractTestOfBonus;

class FatigueBonusTest extends AbstractTestOfBonus
{
    protected function getTableInstance(): AbstractTable
    {
        return new FatigueTable(new WoundsTable());
    }
}