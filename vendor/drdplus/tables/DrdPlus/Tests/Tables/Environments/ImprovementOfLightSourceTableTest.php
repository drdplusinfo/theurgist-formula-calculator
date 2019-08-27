<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Environments;

use DrdPlus\Codes\Environment\LightSourceEnvironmentCode;
use DrdPlus\Tables\Environments\ImprovementOfLightSourceTable;
use DrdPlus\Tests\Tables\TableTest;

class ImprovementOfLightSourceTableTest extends TableTest
{
    /**
     * @test
     */
    public function I_can_get_header()
    {
        self::assertSame(
            [['environment', 'improvement_of_light_source']],
            (new ImprovementOfLightSourceTable())->getHeader()
        );
    }

    /**
     * @test
     * @dataProvider provideEnvironmentAndImprovement
     * @param $lightSourceEnvironmentValue
     * @param $expectedImprovement
     */
    public function I_can_get_improvement_of_light_source_by_environment($lightSourceEnvironmentValue, $expectedImprovement)
    {
        $lightSourceEnvironmentCode = LightSourceEnvironmentCode::getIt($lightSourceEnvironmentValue);
        $improvementOfLightSourceTable = new ImprovementOfLightSourceTable();
        self::assertSame(
            $expectedImprovement,
            $improvementOfLightSourceTable->getLightSourceImprovement($lightSourceEnvironmentCode)
        );
    }

    public function provideEnvironmentAndImprovement()
    {
        return [
            [LightSourceEnvironmentCode::OPEN_SPACE_OR_ROOM_IN_DARK_UNDERGROUND, 0],
            [LightSourceEnvironmentCode::CORRIDOR_IN_DARK_UNDERGROUND_OR_LIGHT_PLASTERED_ROOM, 1],
            [LightSourceEnvironmentCode::LIGHT_PLASTERED_CORRIDOR_OR_ROOM_WITH_NEW_SHINY_PLASTER_OR_COVER_OF_CANDLE_BY_HAND, 2],
            [LightSourceEnvironmentCode::CORRIDOR_WITH_SHINY_NEW_PLASTER, 3],
            [LightSourceEnvironmentCode::MIRROR_BEHIND_LIGHT_SOURCE, 6],
            [LightSourceEnvironmentCode::THREE_SIDE_MIRROR_DIRECTING_LIGHT_FORWARD, 10],
        ];
    }

}