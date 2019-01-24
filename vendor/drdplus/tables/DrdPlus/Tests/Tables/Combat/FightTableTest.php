<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Combat;

use DrdPlus\Tables\Combat\FightTable;
use DrdPlus\Tests\Tables\TableTest;

class FightTableTest extends TableTest
{
    /**
     * @test
     */
    public function I_can_get_header(): void
    {
        self::assertSame([['profession', 'first_property', 'second_property']], (new FightTable())->getHeader());
    }
}