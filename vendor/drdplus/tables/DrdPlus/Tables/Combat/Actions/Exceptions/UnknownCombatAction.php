<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Combat\Actions\Exceptions;

use DrdPlus\Tables\Partials\Exceptions\RequiredDataNotFound;

class UnknownCombatAction extends RequiredDataNotFound implements Logic
{

}