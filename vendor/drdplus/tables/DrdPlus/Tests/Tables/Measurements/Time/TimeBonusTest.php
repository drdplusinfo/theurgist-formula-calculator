<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Measurements\Time;

use DrdPlus\Codes\Units\TimeUnitCode;
use DrdPlus\Tables\Measurements\Time\TimeBonus;
use DrdPlus\Tables\Measurements\Time\TimeTable;
use DrdPlus\Tests\Tables\Measurements\AbstractTestOfBonus;

class TimeBonusTest extends AbstractTestOfBonus
{
    /**
     * @test
     * @expectedException \DrdPlus\Tables\Measurements\Time\Exceptions\CanNotConvertThatBonusToTime
     */
    public function I_can_not_get_time_in_unsupported_bonus_to_unit_conversion()
    {
        $timeBonus = new TimeBonus(0, new TimeTable());
        try {
            self::assertNull($timeBonus->findTime(TimeUnitCode::YEAR));
        } catch (\Exception $exception) {
            self::fail('No exception should happen so far ' . $exception->getTraceAsString());
        }
        $timeBonus->getTime(TimeUnitCode::YEAR);
    }
}