<?php
declare(strict_types=1); 

namespace DrdPlus\Codes\Armaments\EnumTypes\Exceptions;

use Doctrineum\Scalar\Exceptions\EnumClassNotFound;

class ThereIsNoDefaultEnumForWeaponlikeCode extends EnumClassNotFound implements Logic
{

}