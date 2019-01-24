<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Armaments\Projectiles;

use DrdPlus\Codes\Armaments\DartCode;
use DrdPlus\Codes\Body\PhysicalWoundTypeCode;
use DrdPlus\Tables\Armaments\Projectiles\DartsTable;
use DrdPlus\Tests\Tables\Armaments\Projectiles\Partials\ProjectilesTableTest;

class DartsTableTest extends ProjectilesTableTest
{
    protected function getRowHeaderName(): string
    {
        return 'projectile';
    }

    public function provideArmamentAndNameWithValue(): array
    {
        return [
            [DartCode::BASIC_DART, DartsTable::OFFENSIVENESS, 0],
            [DartCode::BASIC_DART, DartsTable::WOUNDS, 0],
            [DartCode::BASIC_DART, DartsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::STAB],
            [DartCode::BASIC_DART, DartsTable::RANGE, 0],
            [DartCode::BASIC_DART, DartsTable::WEIGHT, 0.05],

            [DartCode::WAR_DART, DartsTable::OFFENSIVENESS, 0],
            [DartCode::WAR_DART, DartsTable::WOUNDS, 2],
            [DartCode::WAR_DART, DartsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::STAB],
            [DartCode::WAR_DART, DartsTable::RANGE, -2],
            [DartCode::WAR_DART, DartsTable::WEIGHT, 0.1],

            [DartCode::PIERCING_DART, DartsTable::OFFENSIVENESS, 0],
            [DartCode::PIERCING_DART, DartsTable::WOUNDS, -1],
            [DartCode::PIERCING_DART, DartsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::STAB],
            [DartCode::PIERCING_DART, DartsTable::RANGE, 0],
            [DartCode::PIERCING_DART, DartsTable::WEIGHT, 0.05],

            [DartCode::HOLLOW_DART, DartsTable::OFFENSIVENESS, 0],
            [DartCode::HOLLOW_DART, DartsTable::WOUNDS, -1],
            [DartCode::HOLLOW_DART, DartsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::STAB],
            [DartCode::HOLLOW_DART, DartsTable::RANGE, 0],
            [DartCode::HOLLOW_DART, DartsTable::WEIGHT, 0.05],

            [DartCode::SILVER_DART, DartsTable::OFFENSIVENESS, 0],
            [DartCode::SILVER_DART, DartsTable::WOUNDS, 0],
            [DartCode::SILVER_DART, DartsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::STAB],
            [DartCode::SILVER_DART, DartsTable::RANGE, 0],
            [DartCode::SILVER_DART, DartsTable::WEIGHT, 0.05],
        ];
    }

}