<?php declare(strict_types=1);

namespace DrdPlus\Calculators\Theurgist;

use DrdPlus\CalculatorSkeleton\CalculatorApplication;

class FormulaCalculatorApplication extends CalculatorApplication
{
    public function __construct(FormulaServicesContainer $formulaServicesContainer)
    {
        parent::__construct($formulaServicesContainer);
    }
}