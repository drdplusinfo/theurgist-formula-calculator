<?php
namespace DrdPlus\Theurgist\Configurator;

use DrdPlus\Tables\Measurements\Speed\SpeedBonus;
use DrdPlus\Tables\Tables;
use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Spells\FormulasTable;

/** @var FormulaCode $selectedFormulaCode */
/** @var FormulasTable $formulasTable */

$spellSpeed = $formulasTable->getSpellSpeed($selectedFormulaCode);
if ($spellSpeed === null) {
    return;
}
?>
<div class="parameter panel">
    <label>Rychlost:
        <?php
        $spellSpeedAddition = $spellSpeed->getAdditionByDifficulty();
        $additionStep = $spellSpeedAddition->getAdditionStep();
        $difficultyOfAdditionStep = $spellSpeedAddition->getDifficultyOfAdditionStep();
        $optionSpellSpeedValue = $spellSpeed->getDefaultValue(); // from the lowest
        $previousOptionSpellSpeedValue = null;
        ?>
        <select name="formula-parameter[spellSpeed]">
            <?php
            do {
                if ($previousOptionSpellSpeedValue === null || $previousOptionSpellSpeedValue < $optionSpellSpeedValue) {
                    $optionSpellSpeed = (new SpeedBonus($optionSpellSpeedValue, Tables::getIt()->getSpeedTable()))->getSpeed();
                    $spellSpeedUnitInCzech = $optionSpellSpeed->getUnitCode()->translateTo('cs', $optionSpellSpeed->getValue());
                    ?>
                    <option value="<?= $optionSpellSpeedValue ?>">
                        <?= ($optionSpellSpeedValue >= 0 ? '+' : '')
                        . "{$optionSpellSpeedValue} ({$optionSpellSpeed->getValue()} {$spellSpeedUnitInCzech})"; ?>
                    </option>
                <?php }
                $previousOptionSpellSpeedValue = $optionSpellSpeedValue;
            } while ($additionStep > 0 /* at least once even on no addition possible */
            && $optionSpellSpeedValue++ / $additionStep * $difficultyOfAdditionStep < 21 /* difficulty change */) ?>
        </select>
    </label>
</div>