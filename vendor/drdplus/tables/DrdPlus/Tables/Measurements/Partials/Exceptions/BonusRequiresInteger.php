<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Measurements\Partials\Exceptions;

use Granam\Integer\Tools\Exceptions\WrongParameterType;

class BonusRequiresInteger extends WrongParameterType implements Runtime
{

}
