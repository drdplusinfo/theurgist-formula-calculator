<?php declare(strict_types=1);

namespace DrdPlus\Calculators\Theurgist;

use DrdPlus\CalculatorSkeleton\CalculatorServicesContainer;
use DrdPlus\RulesSkeleton\Web\WebPartsContainer;
use DrdPlus\Tables\Tables;

class FormulaServicesContainer extends CalculatorServicesContainer
{
    private $formulaWebPartsContainer;
    private $currentFormulaValues;

    /**
     * @return WebPartsContainer|FormulaWebPartsContainer
     */
    public function getWebPartsContainer(): WebPartsContainer
    {
        if ($this->formulaWebPartsContainer === null) {
            $this->formulaWebPartsContainer = new FormulaWebPartsContainer(
                $this->getPass(),
                $this->getWebFiles(),
                $this->getDirs(),
                $this->getHtmlHelper(),
                $this->getRequest(),
                $this->getTables(),
                $this->getCurrentFormulaValues()
            );
        }
        return $this->formulaWebPartsContainer;
    }

    public function getTables(): Tables
    {
        return Tables::getIt();
    }

    public function getCurrentFormulaValues(): CurrentFormulaValues
    {
        if ($this->currentFormulaValues === null) {
            $this->currentFormulaValues = new CurrentFormulaValues(
                $this->getCurrentValues(),
                $this->getTables()
            );
        }

        return $this->currentFormulaValues;
    }
}