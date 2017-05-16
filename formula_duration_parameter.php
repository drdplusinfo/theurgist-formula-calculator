<?php
namespace DrdPlus\Theurgist\Configurator;

use DrdPlus\Tables\Measurements\Time\Time;
use DrdPlus\Tables\Measurements\Time\TimeBonus;
use DrdPlus\Tables\Tables;
use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Spells\FormulasTable;

/** @var FormulaCode $selectedFormulaCode */
/** @var FormulasTable $formulasTable */

?>
<div class="parameter panel">
    <label>Doba trvání:
        <?php
        $duration = $formulasTable->getDuration($selectedFormulaCode);
        $durationAddition = $duration->getAdditionByDifficulty();
        $additionStep = $durationAddition->getAdditionStep();
        $difficultyOfAdditionStep = $durationAddition->getDifficultyOfAdditionStep();
        $optionDurationValue = $duration->getDefaultValue(); // from the lowest
        /** @var Time $previousOptionDurationTime */
        $previousOptionDurationTime = null;
        ?>
        <select name="formula-parameter[duration]">
            <?php
            do {
                $optionDurationTime = (new TimeBonus($optionDurationValue, Tables::getIt()->getTimeTable()))->getTime();
                if (!$previousOptionDurationTime
                    || $previousOptionDurationTime->getUnit() !== $optionDurationTime->getUnit()
                    || $previousOptionDurationTime->getValue() < $optionDurationTime->getValue()
                ) {
                    $optionDurationUnitInCzech = $optionDurationTime->getUnitCode()->translateTo('cs', $optionDurationTime->getValue()); ?>
                    <option value="<?= $optionDurationValue ?>">
                        <?= ($optionDurationValue >= 0 ? '+' : '')
                        . "$optionDurationValue ({$optionDurationTime->getValue()} {$optionDurationUnitInCzech})" ?>
                    </option>
                <?php }
                $previousOptionDurationTime = $optionDurationTime;
            } while ($additionStep > 0 && $optionDurationValue++ / $additionStep * $difficultyOfAdditionStep < 21 /* difficulty change */) ?>
        </select>
    </label>
</div>
