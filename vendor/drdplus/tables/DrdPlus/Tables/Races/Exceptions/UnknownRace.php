<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Races\Exceptions;

use DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound;

class UnknownRace extends RequiredRowNotFound implements Logic
{

}