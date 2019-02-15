<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Combat\Actions\Exceptions;

use DrdPlus\Tables\Partials\Exceptions\RequiredDataNotFound;

class UnknownCombatAction extends RequiredDataNotFound implements Logic
{

}