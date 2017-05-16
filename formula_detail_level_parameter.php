<?php
namespace DrdPlus\Theurgist\Configurator;

use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Spells\FormulasTable;

/** @var FormulaCode $selectedFormulaCode */
/** @var FormulasTable $formulasTable */

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
        ?>
        <select name="formula-parameter[detailLevel]">
            <?php
            do {
                if ($previousOptionDetailLevelValue === null || $previousOptionDetailLevelValue < $optionDetailLevelValue) { ?>
                    <option value="<?= $optionDetailLevelValue ?>">
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