<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Environments;

use DrdPlus\Codes\Environment\LandingSurfaceCode;
use DrdPlus\BaseProperties\Agility;
use DrdPlus\Tables\Environments\LandingSurfacesTable;
use DrdPlus\Tests\Tables\TableTest;
use Granam\Integer\IntegerWithHistory;
use Granam\Integer\PositiveIntegerObject;

class LandingSurfacesTableTest extends TableTest
{
    /**
     * @test
     * @dataProvider provideValuesToGetPowerOfWoundModifier
     * @param string $landingSurfaceValue
     * @param int $agilityValue
     * @param int $armorProtectionValue
     * @param int $expectedPowerOfWoundModifier
     */
    public function I_can_get_power_of_wound_modifier(
        string $landingSurfaceValue,
        int $agilityValue,
        int $armorProtectionValue,
        int $expectedPowerOfWoundModifier
    ): void
    {
        $baseOfWoundsModifier = (new LandingSurfacesTable())->getBaseOfWoundsModifier(
            LandingSurfaceCode::getIt($landingSurfaceValue),
            Agility::getIt($agilityValue),
            new PositiveIntegerObject($armorProtectionValue)
        );
        self::assertSame(
            $expectedPowerOfWoundModifier,
            $baseOfWoundsModifier->getValue()
        );
        self::assertInstanceOf(IntegerWithHistory::class, $baseOfWoundsModifier);
        $history = $baseOfWoundsModifier->getHistory();
        self::assertNotEmpty($history);
    }

    public function provideValuesToGetPowerOfWoundModifier(): array
    {
        // surface, agility, armor, expected power of wounds
        return [
            [LandingSurfaceCode::DEEP_POWDER, 9999, 888888, -15],
            [LandingSurfaceCode::WATER, 0, 987654321, -15],
            [LandingSurfaceCode::WATER, -5, 987654321, -10 /* -15 - (3 * -5 / 3) = -15 - -5 = -10 */],
            [LandingSurfaceCode::WATER, 8, 987654321, -39],
            [LandingSurfaceCode::SHARP_ROCKS_OR_POINTED_PALES, 99999, 0, 15],
            [LandingSurfaceCode::SHARP_ROCKS_OR_POINTED_PALES, 99999, 8, 7],
            [LandingSurfaceCode::SHARP_ROCKS_OR_POINTED_PALES, 99999, 876543, 7],
        ];
    }

}