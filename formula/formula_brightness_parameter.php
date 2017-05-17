<?php
namespace DrdPlus\Theurgist\Configurator;

use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Codes\FormulaMutableSpellParameterCode;
use DrdPlus\Theurgist\Spells\FormulasTable;

/** @var FormulaCode $selectedFormulaCode */
/** @var FormulasTable $formulasTable */
/** @var IndexController $controller */

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
        $selectedBrightnessValue = $controller->getSelectedFormulaSpellParameters()[FormulaMutableSpellParameterCode::BRIGHTNESS] ?? false;
        ?>
        <select name="formulaParameters[<?= FormulaMutableSpellParameterCode::BRIGHTNESS ?>]">
            <?php
            do {
                if ($previousOptionBrightnessValue === null || $previousOptionBrightnessValue < $optionBrightnessValue) { ?>
                    <option value="<?= $optionBrightnessValue ?>"
                            <?php if ($selectedBrightnessValue !== false && $selectedBrightnessValue === $optionBrightnessValue) { ?>selected<?php } ?>>
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