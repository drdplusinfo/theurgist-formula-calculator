<?php
declare(strict_types=1);

namespace DrdPlus\Calculators\Theurgist;

use DrdPlus\CalculatorSkeleton\CalculatorServicesContainer;
use DrdPlus\Tables\Tables;

class FormulaServicesContainer extends CalculatorServicesContainer
{
    /** @var CurrentFormulaValues */
    private $currentFormulaValues;

    public function getRulesMainBodyParameters(): array
    {
        return [
            'historyDeletion' => $this->getHistoryDeletionBody(),
            'currentFormulaValues' => $this->getCurrentFormulaValues(),
            'currentFormulaCode' => $this->getCurrentFormulaValues()->getCurrentFormulaCode(),
            'currentFormula' => $this->getCurrentFormulaValues()->getCurrentFormula(),
            'tables' => $this->getTables(),
            'calculatorDebugContacts' => $this->getDebugContactsBody(),
        ];
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