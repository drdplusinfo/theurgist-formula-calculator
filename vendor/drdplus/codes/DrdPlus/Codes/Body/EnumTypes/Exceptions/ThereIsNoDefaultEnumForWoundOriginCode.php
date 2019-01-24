<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */

namespace DrdPlus\Codes\Body\EnumTypes\Exceptions;

use Doctrineum\Scalar\Exceptions\EnumClassNotFound;

class ThereIsNoDefaultEnumForWoundOriginCode extends EnumClassNotFound implements Logic
{

}