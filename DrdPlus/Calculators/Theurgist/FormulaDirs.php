<?php
declare(strict_types=1);

namespace DrdPlus\Calculators\Theurgist;

use DrdPlus\RulesSkeleton\Dirs;

class FormulaDirs extends Dirs
{
    public function getWebRoot(): string
    {
        return parent::getWebRoot() . '/web';
    }

}