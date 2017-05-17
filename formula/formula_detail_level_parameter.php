<?php
namespace DrdPlus\Theurgist\Configurator;

use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Codes\FormulaMutableSpellParameterCode;
use DrdPlus\Theurgist\Spells\FormulasTable;

/** @var FormulaCode $selectedFormulaCode */
/** @var FormulasTable $formulasTable */
/** @var IndexController $controller */

$detailLevel = $formulasTable->getDetailLevel($selectedFormulaCode);
if ($detailLevel === null) {
    return;
} ?>
<div class="parameter panel">
    <label>Detailnost:
        <?php
        $detailLevelAddition = $detailLevel->getAdditionByDifficulty();
        $additionStep = $detailLevelAddition->getAdditionStep();
        $difficultyOfAdditionStep = $detailLevelAddition->getDifficultyOfAdditionStep();
        $optionDetailLevelValue = $detailLevel->getDefaultValue(); // from the lowest
        $previousOptionDetailLevelValue = null;
        $selectedDetailLevelValue = $controller->getSelectedFormulaSpellParameters()[FormulaMutableSpellParameterCode::DETAIL_LEVEL] ?? false;
        ?>
        <select name="formulaParameters[<?= FormulaMutableSpellParameterCode::DETAIL_LEVEL ?>]">
            <?php
            do {
                if ($previousOptionDetailLevelValue === null || $previousOptionDetailLevelValue < $optionDetailLevelValue) { ?>
                    <option value="<?= $optionDetailLevelValue ?>"
                            <?php if ($selectedDetailLevelValue !== false && $selectedDetailLevelValue === $optionDetailLevelValue){ ?>selected<?php } ?>>
                        <?= ($optionDetailLevelValue >= 0 ? '+' : '')
                        . "{$optionDetailLevelValue}"; ?>
                    </option>
                <?php }
                $previousOptionDetailLevelValue = $optionDetailLevelValue;
            } while ($additionStep > 0 /* at least once even on no addition possible */
            && $optionDetailLevelValue++ / $additionStep * $difficultyOfAdditionStep < 21 /* difficulty change */) ?>
        </select>
    </label>
</div>