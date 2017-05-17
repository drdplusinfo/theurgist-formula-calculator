<?php
namespace DrdPlus\Theurgist\Configurator;

use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Codes\FormulaMutableSpellParameterCode;
use DrdPlus\Theurgist\Spells\FormulasTable;

/** @var FormulaCode $selectedFormulaCode */
/** @var FormulasTable $formulasTable */
/** @var IndexController $controller */

$power = $formulasTable->getPower($selectedFormulaCode);
if ($power === null) {
    return;
} ?>
<div class="parameter panel">
    <label>Síla:
        <?php
        $powerAddition = $power->getAdditionByDifficulty();
        $additionStep = $powerAddition->getAdditionStep();
        $difficultyOfAdditionStep = $powerAddition->getDifficultyOfAdditionStep();
        $optionPowerValue = $power->getDefaultValue(); // from the lowest
        $previousOptionPowerValue = null;
        $selectedPowerValue = $controller->getSelectedFormulaSpellParameters()[FormulaMutableSpellParameterCode::POWER] ?? false;
        ?>
        <select name="formulaParameters[<?= FormulaMutableSpellParameterCode::POWER ?>]">
            <?php
            do {
                if ($previousOptionPowerValue === null || $previousOptionPowerValue < $optionPowerValue) { ?>
                    <option value="<?= $optionPowerValue ?>"
                            <?php if ($selectedPowerValue !== false && $selectedPowerValue === $optionPowerValue){ ?>selected<?php } ?>>
                        <?= ($optionPowerValue >= 0 ? '+' : '')
                        . "{$optionPowerValue}"; ?>
                    </option>
                <?php }
                $previousOptionPowerValue = $optionPowerValue;
            } while ($additionStep > 0 /* at least once even on no addition possible */
            && $optionPowerValue++ / $additionStep * $difficultyOfAdditionStep < 21 /* difficulty change */) ?>
        </select>
    </label>
</div>