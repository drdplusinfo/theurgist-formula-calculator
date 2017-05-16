<?php
namespace DrdPlus\Theurgist\Configurator;

use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Spells\FormulasTable;

/** @var FormulaCode $selectedFormulaCode */
/** @var FormulasTable $formulasTable */

$sizeChange = $formulasTable->getSizeChange($selectedFormulaCode);
if ($sizeChange === null) {
    return;
}
?>
<div class="parameter panel">
    <label>Změna velikosti:
        <?php
        $sizeChangeAddition = $sizeChange->getAdditionByDifficulty();
        $additionStep = $sizeChangeAddition->getAdditionStep();
        $difficultyOfAdditionStep = $sizeChangeAddition->getDifficultyOfAdditionStep();
        $optionSizeChangeValue = $sizeChange->getDefaultValue(); // from the lowest
        $previousOptionSizeChangeValue = null;
        ?>
        <select name="formula-parameter[sizeChange]">
            <?php
            do {
                if ($previousOptionSizeChangeValue === null || $previousOptionSizeChangeValue < $optionSizeChangeValue) { ?>
                    <option value="<?= $optionSizeChangeValue ?>">
                        <?= ($optionSizeChangeValue >= 0 ? '+' : '')
                        . "{$optionSizeChangeValue}"; ?>
                    </option>
                <?php }
                $previousOptionSizeChangeValue = $optionSizeChangeValue;
            } while ($additionStep > 0 /* at least once even on no addition possible */
            && $optionSizeChangeValue++ / $additionStep * $difficultyOfAdditionStep < 21 /* difficulty change */) ?>
        </select>
    </label>
</div>