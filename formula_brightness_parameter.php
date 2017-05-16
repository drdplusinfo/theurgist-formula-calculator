<?php
namespace DrdPlus\Theurgist\Configurator;

use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Spells\FormulasTable;

/** @var FormulaCode $selectedFormulaCode */
/** @var FormulasTable $formulasTable */

$brightness = $formulasTable->getBrightness($selectedFormulaCode);
if ($brightness === null) {
    return;
} ?>
<div class="parameter panel">
    <label>Jas:
        <?php
        $brightnessAddition = $brightness->getAdditionByDifficulty();
        $additionStep = $brightnessAddition->getAdditionStep();
        $difficultyOfAdditionStep = $brightnessAddition->getDifficultyOfAdditionStep();
        $optionBrightnessValue = $brightness->getDefaultValue(); // from the lowest
        $previousOptionBrightnessValue = null;
        ?>
        <select name="formula-parameter[brightness]">
            <?php
            do {
                if ($previousOptionBrightnessValue === null || $previousOptionBrightnessValue < $optionBrightnessValue) { ?>
                    <option value="<?= $optionBrightnessValue ?>">
                        <?= ($optionBrightnessValue >= 0 ? '+' : '')
                        . "{$optionBrightnessValue}"; ?>
                    </option>
                <?php }
                $previousOptionBrightnessValue = $optionBrightnessValue;
            } while ($additionStep > 0 /* at least once even on no addition possible */
            && $optionBrightnessValue++ / $additionStep * $difficultyOfAdditionStep < 21 /* difficulty change */) ?>
        </select>
    </label>
</div>