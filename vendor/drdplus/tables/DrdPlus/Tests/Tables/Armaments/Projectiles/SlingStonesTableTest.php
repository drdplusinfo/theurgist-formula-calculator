<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Armaments\Projectiles;

use DrdPlus\Codes\Armaments\SlingStoneCode;
use DrdPlus\Codes\Body\PhysicalWoundTypeCode;
use DrdPlus\Tables\Armaments\Projectiles\SlingStonesTable;
use DrdPlus\Tests\Tables\Armaments\Projectiles\Partials\ProjectilesTableTest;

class SlingStonesTableTest extends ProjectilesTableTest
{
    protected function getRowHeaderName(): string
    {
        return 'projectile';
    }

    public function provideArmamentAndNameWithValue(): array
    {
        return [
            [SlingStoneCode::SLING_STONE_LIGHT, SlingStonesTable::OFFENSIVENESS, 0],
            [SlingStoneCode::SLING_STONE_LIGHT, SlingStonesTable::WOUNDS, 0],
            [SlingStoneCode::SLING_STONE_LIGHT, SlingStonesTable::WOUNDS_TYPE, PhysicalWoundTypeCode::CRUSH],
            [SlingStoneCode::SLING_STONE_LIGHT, SlingStonesTable::RANGE, 0],
            [SlingStoneCode::SLING_STONE_LIGHT, SlingStonesTable::WEIGHT, 0.1],

            [SlingStoneCode::SLING_STONE_HEAVIER, SlingStonesTable::OFFENSIVENESS, 0],
            [SlingStoneCode::SLING_STONE_HEAVIER, SlingStonesTable::WOUNDS, 2],
            [SlingStoneCode::SLING_STONE_HEAVIER, SlingStonesTable::WOUNDS_TYPE, PhysicalWoundTypeCode::CRUSH],
            [SlingStoneCode::SLING_STONE_HEAVIER, SlingStonesTable::RANGE, -2],
            [SlingStoneCode::SLING_STONE_HEAVIER, SlingStonesTable::WEIGHT, 0.2],
        ];
    }

}