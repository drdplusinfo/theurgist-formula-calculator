<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\History\Exceptions;

use DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound;

class UnexpectedBackgroundPoints extends RequiredRowNotFound implements Logic
{

}