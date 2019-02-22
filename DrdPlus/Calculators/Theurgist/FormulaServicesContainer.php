<?php
declare(strict_types=1);

namespace DrdPlus\Calculators\Theurgist;

use DrdPlus\CalculatorSkeleton\CalculatorServicesContainer;
use DrdPlus\Tables\Tables;

class FormulaServicesContainer extends CalculatorServicesContainer
{
    /** @var FormulaWebPartsContainer */
    private $formulaWebPartsContainer;
    /** @var CurrentFormulaValues */
    private $currentFormulaValues;

    public function getWebPartsContainer(): \DrdPlus\RulesSkeleton\Web\WebPartsContainer
    {
        if ($this->formulaWebPartsContainer === null) {
            $this->formulaWebPartsContainer = new FormulaWebPartsContainer(
                $this->getPass(),
                $this->getWebFiles(),
                $this->getDirs(),
                $this->getHtmlHelper(),
                $this->getRequest(),
                $this->getCurrentFormulaValues(),
                $this->getTables()
            );
        }
        return $this->formulaWebPartsContainer;
    }

    public function getCurrentFormulaValues(): CurrentFormulaValues
    {
        if ($this->currentFormulaValues === null) {
            $this->currentFormulaValues = new CurrentFormulaValues($this->getCurrentValues(), $this->getTables());
        }

        return $this->currentFormulaValues;
    }

    public function getTables(): Tables
    {
        return Tables::getIt();
    }
}