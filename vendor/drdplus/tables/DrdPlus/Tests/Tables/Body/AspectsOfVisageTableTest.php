<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Body;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Tables\Body\AspectsOfVisageTable;
use DrdPlus\Tests\Tables\TableTest;

class AspectsOfVisageTableTest extends TableTest
{
    /**
     * @test
     */
    public function I_can_get_header(): void
    {
        self::assertSame(
            [['aspect_of_visage', 'first_property', 'second_property', 'sum_of_first_and_second_property_divisor', 'third_property', 'third_property_divisor']],
            (new AspectsOfVisageTable())->getHeader()
        );
    }

    /**
     * @test
     */
    public function I_can_get_its_indexed_values()
    {
        $genericAspect = [
            AspectsOfVisageTable::SUM_OF_FIRST_AND_SECOND_PROPERTY_DIVISOR => 2,
            AspectsOfVisageTable::THIRD_PROPERTY => PropertyCode::CHARISMA,
            AspectsOfVisageTable::THIRD_PROPERTY_DIVISOR => 2,
        ];
        $expectedAspects = [
            PropertyCode::BEAUTY => array_merge(
                [AspectsOfVisageTable::FIRST_PROPERTY => PropertyCode::AGILITY, AspectsOfVisageTable::SECOND_PROPERTY => PropertyCode::KNACK],
                $genericAspect
            ),
            PropertyCode::DANGEROUSNESS => array_merge(
                [AspectsOfVisageTable::FIRST_PROPERTY => PropertyCode::STRENGTH, AspectsOfVisageTable::SECOND_PROPERTY => PropertyCode::WILL],
                $genericAspect
            ),
            PropertyCode::DIGNITY => array_merge(
                [AspectsOfVisageTable::FIRST_PROPERTY => PropertyCode::INTELLIGENCE, AspectsOfVisageTable::SECOND_PROPERTY => PropertyCode::WILL],
                $genericAspect
            ),
        ];
        self::assertSame($expectedAspects, (new AspectsOfVisageTable())->getIndexedValues());
    }
}