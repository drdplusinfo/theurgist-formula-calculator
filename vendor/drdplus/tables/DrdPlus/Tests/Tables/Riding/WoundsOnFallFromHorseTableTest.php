<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Riding;

use DrdPlus\Codes\Transport\RidingAnimalMovementCode;
use DrdPlus\Tables\Riding\WoundsOnFallFromHorseTable;
use DrdPlus\Tables\Measurements\Wounds\WoundsBonus;
use DrdPlus\Tables\Measurements\Wounds\WoundsTable;
use DrdPlus\Tests\Tables\TableTest;

class WoundsOnFallFromHorseTableTest extends TableTest
{
    /**
     * @test
     */
    public function I_can_get_header()
    {
        self::assertSame(
            [
                ['activity', 'wounds_modification', 'additional']
            ],
            (new WoundsOnFallFromHorseTable())->getHeader()
        );
    }

    /**
     * @test
     */
    public function I_can_get_all_values()
    {
        self::assertSame(
            [
                WoundsOnFallFromHorseTable::STILL => [
                    WoundsOnFallFromHorseTable::WOUNDS_MODIFICATION => 0,
                    WoundsOnFallFromHorseTable::ADDITIONAL => false,
                ],
                RidingAnimalMovementCode::GAIT => [
                    WoundsOnFallFromHorseTable::WOUNDS_MODIFICATION => 0,
                    WoundsOnFallFromHorseTable::ADDITIONAL => false,
                ],
                RidingAnimalMovementCode::TROT => [
                    WoundsOnFallFromHorseTable::WOUNDS_MODIFICATION => 3,
                    WoundsOnFallFromHorseTable::ADDITIONAL => false,
                ],
                RidingAnimalMovementCode::CANTER => [
                    WoundsOnFallFromHorseTable::WOUNDS_MODIFICATION => 9,
                    WoundsOnFallFromHorseTable::ADDITIONAL => false,
                ],
                RidingAnimalMovementCode::GALLOP => [
                    WoundsOnFallFromHorseTable::WOUNDS_MODIFICATION => 12,
                    WoundsOnFallFromHorseTable::ADDITIONAL => false,
                ],
                RidingAnimalMovementCode::JUMPING => [
                    WoundsOnFallFromHorseTable::WOUNDS_MODIFICATION => 3,
                    WoundsOnFallFromHorseTable::ADDITIONAL => true,
                ],
            ],
            (new WoundsOnFallFromHorseTable())->getIndexedValues()
        );
    }

    /**
     * @test
     * @dataProvider provideFallAndExpectedWounds
     * @param $activity
     * @param $jumping
     * @param $expectedWoundsBonus
     */
    public function I_can_get_wounds_on_fall_from_horse(string $activity, bool $jumping, int $expectedWoundsBonus)
    {
        $woundsTable = new WoundsTable();
        self::assertEquals(
            new WoundsBonus($expectedWoundsBonus, $woundsTable),
            (new WoundsOnFallFromHorseTable())->getWoundsAdditionOnFallFromHorse(
                RidingAnimalMovementCode::getIt($activity),
                $jumping,
                $woundsTable
            )
        );
    }

    public function provideFallAndExpectedWounds(): array
    {
        return [
            [WoundsOnFallFromHorseTable::STILL, false, 0],
            [WoundsOnFallFromHorseTable::STILL, true, 3],
            [WoundsOnFallFromHorseTable::GALLOP, false, 12]
        ];
    }

}