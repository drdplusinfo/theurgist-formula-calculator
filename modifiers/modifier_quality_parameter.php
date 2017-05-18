<?php
namespace DrdPlus\Theurgist\Configurator;

use DrdPlus\Theurgist\Codes\ModifierCode;
use DrdPlus\Theurgist\Codes\ModifierMutableSpellParameterCode;
use DrdPlus\Theurgist\Spells\ModifiersTable;

/** @var ModifierCode $possibleModifier */
/** @var ModifiersTable $modifiersTable */
/** @var IndexController $controller */
/** @var int $treeLevel */
/** @var string $possibleModifierValue */
/** @var string $modifiersIndex */

$quality = $modifiersTable->getQuality($possibleModifier);
if ($quality === null) {
    return;
} ?>
<div class="parameter">
    <label>Kvalita:
        <?php
        $qualityAddition = $quality->getAdditionByDifficulty();
        $additionStep = $qualityAddition->getAdditionStep();
        $difficultyOfAdditionStep = $qualityAddition->getDifficultyOfAdditionStep();
        $optionQualityValue = $quality->getDefaultValue(); // from the lowest
        $previousOptionQualityValue = null;
        $selectedQualityValue = $controller->getSelectedModifiersSpellParametersTree()[$treeLevel][$possibleModifierValue][ModifierMutableSpellParameterCode::QUALITY] ?? false;
        ?>
        <select name="modifierParameters[<?= $treeLevel ?>][<?= $possibleModifierValue ?>][<?= ModifierMutableSpellParameterCode::QUALITY ?>]">
            <?php
            do {
                if ($previousOptionQualityValue === null || $previousOptionQualityValue < $optionQualityValue) { ?>
                    <option value="<?= $optionQualityValue ?>"
                            <?php if ($selectedQualityValue !== false && $selectedQualityValue === $optionQualityValue){ ?>selected<?php } ?>>
                        <?= ($optionQualityValue >= 0 ? '+' : '')
                        . "{$optionQualityValue}"; ?>
                    </option>
                <?php }
                $previousOptionQualityValue = $optionQualityValue;
            } while ($additionStep > 0 /* at least once even on no addition possible */
            && $optionQualityValue++ / $additionStep * $difficultyOfAdditionStep < 21 /* difficulty change */) ?>
        </select>
    </label>
</div>