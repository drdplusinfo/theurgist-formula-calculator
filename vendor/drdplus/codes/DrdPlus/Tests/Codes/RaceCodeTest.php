<?php
namespace DrdPlus\Tests\Codes;

use DrdPlus\Codes\RaceCode;
use DrdPlus\Codes\SubRaceCode;
use DrdPlus\Tests\Codes\Partials\TranslatableCodeTest;

class RaceCodeTest extends TranslatableCodeTest
{
    /**
     * @test
     */
    public function I_can_get_default_sub_race()
    {
        $raceCode = RaceCode::getIt(RaceCode::HOBBIT);
        self::assertSame(SubRaceCode::getDefaultSubRaceFor($raceCode), $raceCode->getDefaultSubRaceCode());
    }
}