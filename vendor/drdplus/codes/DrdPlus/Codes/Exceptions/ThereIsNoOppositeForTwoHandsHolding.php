<?php
declare(strict_types=1); 

namespace DrdPlus\Codes\Exceptions;

use Doctrineum\Scalar\Exceptions\EnumClassNotFound;

class ThereIsNoOppositeForTwoHandsHolding extends EnumClassNotFound implements Logic
{

}