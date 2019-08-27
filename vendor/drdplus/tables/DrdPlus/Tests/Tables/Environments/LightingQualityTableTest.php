<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Environments;

use DrdPlus\Codes\Environment\LightConditionsCode;
use DrdPlus\Tables\Environments\LightingQualityTable;
use DrdPlus\Tests\Tables\TableTest;

class LightingQualityTableTest extends TableTest
{
    /**
     * @test
     */
    public function I_can_get_header()
    {
        self::assertEquals([['light_conditions', 'quality']], (new LightingQualityTable())->getHeader());
    }

    /**
     * @test
     * @dataProvider provideConditionsAndExpectedQualityOfLight
     * @param string $lightConditionsValue
     * @param int $expectedLightQuality
     */
    public function I_can_get_lighting_quality($lightConditionsValue, $expectedLightQuality)
    {
        $lightingQualityTable = new LightingQualityTable();
        self::assertSame(
            $expectedLightQuality,
            $lightingQualityTable->getLightingQualityOnConditions(LightConditionsCode::getIt($lightConditionsValue))
        );
    }

    public function provideConditionsAndExpectedQualityOfLight()
    {
        return [
            [LightConditionsCode::DARK, -200],
            [LightConditionsCode::CLOUDY_STAR_NIGHT, -120],
            [LightConditionsCode::STAR_NIGHT, -90],
            [LightConditionsCode::FULL_MOON_NIGHT, -50],
            [LightConditionsCode::SUNSET, -10],
            [LightConditionsCode::VERY_CLOUDY, 15],
            [LightConditionsCode::CLOUDY, 40],
            [LightConditionsCode::DAYLIGHT, 50],
            [LightConditionsCode::STRONG_DAYLIGHT, 60],
        ];
    }

}