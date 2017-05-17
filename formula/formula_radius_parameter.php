<?php
namespace DrdPlus\Theurgist\Configurator;

use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Tables;
use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Codes\FormulaMutableSpellParameterCode;
use DrdPlus\Theurgist\Spells\FormulasTable;

/** @var FormulaCode $selectedFormulaCode */
/** @var FormulasTable $formulasTable */
/** @var IndexController $controller */

$radius = $formulasTable->getRadius($selectedFormulaCode);
if ($radius === null) {
    return;
} ?>
<div class="parameter panel">
    <label>Poloměr:
        <?php
        $radiusAddition = $radius->getAdditionByDifficulty();
        $additionStep = $radiusAddition->getAdditionStep();
        $difficultyOfAdditionStep = $radiusAddition->getDifficultyOfAdditionStep();
        $optionRadiusValue = $radius->getDefaultValue(); // from the lowest
        /** @var Distance $previousOptionRadiusDistance */
        $previousOptionRadiusDistance = null;
        $selectedRadiusValue = $controller->getSelectedFormulaSpellParameters()[FormulaMutableSpellParameterCode::RADIUS] ?? false;
        ?>
        <select name="formulaParameters[<?= FormulaMutableSpellParameterCode::RADIUS ?>]">
            <?php
            do {
                $optionRadiusDistance = (new DistanceBonus($optionRadiusValue, Tables::getIt()->getDistanceTable()))->getDistance();
                if (!$previousOptionRadiusDistance
                    || $previousOptionRadiusDistance->getUnit() !== $optionRadiusDistance->getUnit()
                    || $previousOptionRadiusDistance->getValue() < $optionRadiusDistance->getValue()
                ) {
                    $radiusUnitInCzech = $optionRadiusDistance->getUnitCode()->translateTo('cs', $optionRadiusDistance->getValue()); ?>
                    <option value="<?= $optionRadiusValue ?>"
                            <?php if ($selectedRadiusValue !== false && $selectedRadiusValue === $optionRadiusValue){ ?>selected<?php } ?>>
                        <?= ($optionRadiusValue >= 0 ? '+' : '')
                        . "{$optionRadiusValue} ({$optionRadiusDistance->getValue()} {$radiusUnitInCzech})"; ?>
                    </option>
                <?php }
                $previousOptionRadiusDistance = $optionRadiusDistance;
            } while ($additionStep > 0 /* at least once even on no addition possible */
            && $optionRadiusValue++ / $additionStep * $difficultyOfAdditionStep < 21 /* difficulty change */) ?>
        </select>
    </label>
</div>