<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Body;

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
}