<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Measurements\Partials\Exceptions;

use Granam\Integer\Tools\Exceptions\WrongParameterType;

class BonusRequiresInteger extends WrongParameterType implements Runtime
{

}
