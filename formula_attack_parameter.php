<?php
namespace DrdPlus\Theurgist\Configurator;

use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Spells\FormulasTable;

/** @var FormulaCode $selectedFormulaCode */
/** @var FormulasTable $formulasTable */

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
        ?>
        <select name="formula-parameter[attack]">
            <?php
            do {
                if ($previousOptionAttackValue === null || $previousOptionAttackValue < $optionAttackValue) { ?>
                    <option value="<?= $optionAttackValue ?>">
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