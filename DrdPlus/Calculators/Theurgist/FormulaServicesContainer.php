<?php declare(strict_types=1);

namespace DrdPlus\Calculators\Theurgist;

use DrdPlus\CalculatorSkeleton\CalculatorServicesContainer;
use DrdPlus\RulesSkeleton\Dirs;
use DrdPlus\RulesSkeleton\RoutedDirs;
use DrdPlus\RulesSkeleton\Web\WebFiles;
use DrdPlus\RulesSkeleton\Web\WebPartsContainer;
use DrdPlus\Tables\Tables;

class FormulaServicesContainer extends CalculatorServicesContainer
{
    /** @var FormulaWebPartsContainer */
    private $formulaWebPartsContainer;
    /** @var CurrentFormulaValues */
    private $currentFormulaValues;

    /**
     * @return WebPartsContainer|FormulaWebPartsContainer
     */
    public function getRoutedWebPartsContainer(): WebPartsContainer
    {
        if ($this->formulaWebPartsContainer === null) {
            $this->formulaWebPartsContainer = $this->createFormulaWebPartsContainer($this->getRoutedWebFiles());
        }
        return $this->formulaWebPartsContainer;
    }

    private function createFormulaWebPartsContainer(WebFiles $webFiles): FormulaWebPartsContainer
    {
        return new FormulaWebPartsContainer(
            $this->getPass(),
            $webFiles,
            $this->getDirs(),
            $this->getHtmlHelper(),
            $this->getRequest(),
            $this->getCurrentFormulaValues(),
            $this->getTables()
        );
    }

    public function getRootWebPartsContainer(): WebPartsContainer
    {
        if ($this->formulaWebPartsContainer === null) {
            $this->formulaWebPartsContainer = $this->createFormulaWebPartsContainer($this->getRootWebFiles());
        }
        return $this->formulaWebPartsContainer;
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

    public function getTables(): Tables
    {
        return Tables::getIt();
    }

    protected function createRoutedDirs(Dirs $dirs): RoutedDirs
    {
        return new FormulaDirs($dirs->getProjectRoot(), $this->getPathProvider());
    }
}