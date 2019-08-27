<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Measurements\Time\Exceptions;

use DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound;

class UnexpectedHoursPerDayForTimeBonusAdjustment extends RequiredRowNotFound implements Logic
{

}