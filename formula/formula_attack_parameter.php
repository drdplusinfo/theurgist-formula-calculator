<?php
namespace DrdPlus\Theurgist\Configurator;

use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Codes\FormulaMutableCastingParameterCode;
use DrdPlus\Theurgist\Spells\FormulasTable;

/** @var FormulaCode $selectedFormulaCode */
/** @var FormulasTable $formulasTable */
/** @var IndexController $controller */

$attack = $formulasTable->getAttack($selectedFormulaCode);
if ($attack === null) {
    return;
} ?>
<div class="parameter panel">
    <label>Útočnost:
        <?php
        $attackAddition = $attack->getAdditionByDifficulty();
        $additionStep = $attackAddition->getAdditionStep();
        $difficultyOfAdditionStep = $attackAddition->getDifficultyOfAdditionStep();
        $optionAttackValue = $attack->getDefaultValue(); // from the lowest
        $previousOptionAttackValue = null;
        $selectedAttackValue = $controller->getSelectedFormulaSpellParameters()[FormulaMutableCastingParameterCode::ATTACK] ?? false;
        ?>
        <select name="formulaParameters[<?= FormulaMutableCastingParameterCode::ATTACK ?>]">
            <?php
            do {
                if ($previousOptionAttackValue === null || $previousOptionAttackValue < $optionAttackValue) { ?>
                    <option value="<?= $optionAttackValue ?>"
                            <?php if ($selectedAttackValue !== false && $selectedAttackValue === $optionAttackValue){ ?>selected<?php } ?>>
                        <?= ($optionAttackValue >= 0 ? '+' : '')
                        . "{$optionAttackValue}"; ?>
                    </option>
                <?php }
                $previousOptionAttackValue = $optionAttackValue;
            } while ($additionStep > 0 /* at least once even on no addition possible */
            && $optionAttackValue++ / $additionStep * $difficultyOfAdditionStep < 21 /* difficulty change */) ?>
        </select>
    </label>
</div>