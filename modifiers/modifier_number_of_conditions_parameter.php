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

$numberOfConditions = $modifiersTable->getNumberOfConditions($possibleModifier);
if ($numberOfConditions === null) {
    return;
} ?>
<div class="parameter">
    <label>Počet podmínek:
        <?php
        $numberOfConditionsAddition = $numberOfConditions->getAdditionByDifficulty();
        $additionStep = $numberOfConditionsAddition->getAdditionStep();
        $difficultyOfAdditionStep = $numberOfConditionsAddition->getDifficultyOfAdditionStep();
        $optionNumberOfConditionsValue = $numberOfConditions->getDefaultValue(); // from the lowest
        $previousOptionNumberOfConditionsValue = null;
        $selectedNumberOfConditionsValue = $controller->getSelectedModifiersSpellParametersTree()[$treeLevel][$possibleModifierValue][ModifierMutableSpellParameterCode::NUMBER_OF_CONDITIONS] ?? false;
        ?>
        <select name="modifierParameters[<?= $treeLevel ?>][<?= $possibleModifierValue ?>][<?= ModifierMutableSpellParameterCode::NUMBER_OF_CONDITIONS ?>]">
            <?php
            do {
                if ($previousOptionNumberOfConditionsValue === null || $previousOptionNumberOfConditionsValue < $optionNumberOfConditionsValue) { ?>
                    <option value="<?= $optionNumberOfConditionsValue ?>"
                            <?php if ($selectedNumberOfConditionsValue !== false && $selectedNumberOfConditionsValue === $optionNumberOfConditionsValue){ ?>selected<?php } ?>>
                        <?= ($optionNumberOfConditionsValue >= 0 ? '+' : '')
                        . "{$optionNumberOfConditionsValue}"; ?>
                    </option>
                <?php }
                $previousOptionNumberOfConditionsValue = $optionNumberOfConditionsValue;
            } while ($additionStep > 0 /* at least once even on no addition possible */
            && $optionNumberOfConditionsValue++ / $additionStep * $difficultyOfAdditionStep < 21 /* difficulty change */) ?>
        </select>
    </label>
</div>