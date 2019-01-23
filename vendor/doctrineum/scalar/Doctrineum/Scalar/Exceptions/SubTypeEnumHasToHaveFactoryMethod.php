<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */

namespace Doctrineum\Scalar\Exceptions;

class SubTypeEnumHasToHaveFactoryMethod extends \LogicException implements Logic
{

}