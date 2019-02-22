<?php
declare(strict_types=1);

namespace DrdPlus\Calculators\Theurgist;

use DrdPlus\CalculatorSkeleton\Web\CalculatorWebPartsContainer;
use DrdPlus\Codes\Theurgist\FormulaCode;
use DrdPlus\RulesSkeleton\Dirs;
use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\RulesSkeleton\Request;
use DrdPlus\RulesSkeleton\Web\Pass;
use DrdPlus\RulesSkeleton\Web\WebFiles;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Spells\Formula;

class FormulaWebPartsContainer extends CalculatorWebPartsContainer
{
    /**
     * @var CurrentFormulaValues
     */
    private $currentFormulaValues;
    /**
     * @var Tables
     */
    private $tables;

    public function __construct(
        Pass $pass,
        WebFiles $webFiles,
        Dirs $dirs,
        HtmlHelper $htmlHelper,
        Request $request,
        CurrentFormulaValues $currentFormulaValues,
        Tables $tables
    )
    {
        parent::__construct($pass, $webFiles, $dirs, $htmlHelper, $request);
        $this->currentFormulaValues = $currentFormulaValues;
        $this->tables = $tables;
    }

    public function getCurrentFormulaCode(): FormulaCode
    {
        return $this->getCurrentFormulaValues()->getCurrentFormulaCode();
    }

    public function getCurrentFormula(): Formula
    {
        return $this->getCurrentFormulaValues()->getCurrentFormula();
    }

    public function getCurrentFormulaValues(): CurrentFormulaValues
    {
        return $this->currentFormulaValues;
    }

    public function getTables(): Tables
    {
        return $this->tables;
    }
}