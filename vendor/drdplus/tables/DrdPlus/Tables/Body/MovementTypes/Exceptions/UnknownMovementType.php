<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Body\MovementTypes\Exceptions;

use DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound;

class UnknownMovementType extends RequiredRowNotFound implements Logic
{

}