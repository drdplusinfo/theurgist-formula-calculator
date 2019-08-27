<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Measurements;

use DrdPlus\Tests\Tables\TableTest;

abstract class MeasurementTableTest extends TableTest
{
    abstract public function I_can_convert_bonus_to_value();

    abstract public function I_can_not_use_too_low_bonus_to_value();

    abstract public function I_can_convert_value_to_bonus();

    abstract public function I_can_not_convert_too_high_bonus_into_too_detailed_unit();

    abstract public function I_can_not_convert_too_low_value_to_bonus();

    abstract public function I_can_not_convert_too_high_value_to_bonus();
}