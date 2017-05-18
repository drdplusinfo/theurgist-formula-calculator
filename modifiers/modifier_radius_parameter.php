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

$radius = $modifiersTable->getRadius($possibleModifier);
if ($radius === null) {
    return;
} ?>
<div class="parameter">
    <label>Poloměr:
        <?php
        $radiusAddition = $radius->getAdditionByDifficulty();
        $additionStep = $radiusAddition->getAdditionStep();
        $difficultyOfAdditionStep = $radiusAddition->getDifficultyOfAdditionStep();
        $optionRadiusValue = $radius->getDefaultValue(); // from the lowest
        $previousOptionRadiusValue = null;
        $selectedRadiusValue = $controller->getSelectedModifiersSpellParametersTree()[$treeLevel][$possibleModifierValue][ModifierMutableSpellParameterCode::RADIUS] ?? false;
        ?>
        <select name="modifierParameters[<?= $treeLevel ?>][<?= $possibleModifierValue ?>][<?= ModifierMutableSpellParameterCode::RADIUS ?>]">
            <?php
            do {
                if ($previousOptionRadiusValue === null || $previousOptionRadiusValue < $optionRadiusValue) { ?>
                    <option value="<?= $optionRadiusValue ?>"
                            <?php if ($selectedRadiusValue !== false && $selectedRadiusValue === $optionRadiusValue){ ?>selected<?php } ?>>
                        <?= ($optionRadiusValue >= 0 ? '+' : '')
                        . "{$optionRadiusValue}"; ?>
                    </option>
                <?php }
                $previousOptionRadiusValue = $optionRadiusValue;
            } while ($additionStep > 0 /* at least once even on no addition possible */
            && $optionRadiusValue++ / $additionStep * $difficultyOfAdditionStep < 21 /* difficulty change */) ?>
        </select>
    </label>
</div>