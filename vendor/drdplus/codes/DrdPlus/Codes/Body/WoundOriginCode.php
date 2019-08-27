<?php declare(strict_types=1); 

namespace DrdPlus\Codes\Body;

use DrdPlus\Codes\Partials\TranslatableCode;

abstract class WoundOriginCode extends TranslatableCode
{
    abstract public function isSeriousWoundOrigin(): bool;

    abstract public function isOrdinaryWoundOrigin(): bool;

}