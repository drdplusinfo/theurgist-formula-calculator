<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Body;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Tables\Body\WoundAndFatigueBoundariesTable;
use DrdPlus\Tests\Tables\TableTest;

class WoundAndFatigueBoundariesTableTest extends TableTest
{
    /**
     * @test
     */
    public function I_can_get_header(): void
    {
        self::assertSame([['boundary', 'property']], (new WoundAndFatigueBoundariesTable())->getHeader());
    }

    /**
     * @test
     */
    public function I_can_get_its_indexed_values()
    {
        self::assertSame(
            [
                PropertyCode::WOUND_BOUNDARY => [WoundAndFatigueBoundariesTable::PROPERTY => PropertyCode::TOUGHNESS],
                PropertyCode::FATIGUE_BOUNDARY => [WoundAndFatigueBoundariesTable::PROPERTY => PropertyCode::ENDURANCE],
            ],
            (new WoundAndFatigueBoundariesTable())->getIndexedValues()
        );
    }
}