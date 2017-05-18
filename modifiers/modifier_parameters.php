<?php
namespace DrdPlus\Theurgist\Configurator;

use DrdPlus\Theurgist\Codes\ModifierCode;
use DrdPlus\Theurgist\Codes\ModifierMutableSpellParameterCode;
use DrdPlus\Theurgist\Spells\ModifiersTable;
use DrdPlus\Theurgist\Spells\SpellParameters\Partials\IntegerCastingParameter;
use Granam\String\StringTools;

/** @var ModifierCode $possibleModifier */
/** @var ModifiersTable $modifiersTable */
/** @var IndexController $controller */
/** @var int $treeLevel */
/** @var string $possibleModifierValue */
/** @var string $modifiersIndex */

foreach (ModifierMutableSpellParameterCode::getPossibleValues() as $possibleParameterName) {
    $getParameter = StringTools::assembleGetterForName($possibleParameterName);
    $parameter = $modifiersTable->$getParameter($possibleModifier);
    if ($parameter === null) {
        continue;
    }
    /** @var IntegerCastingParameter $parameter */
    $parameterCode = ModifierMutableSpellParameterCode::getIt($possibleParameterName);
    ?>
    <div class="parameter">
        <label><?= $parameterCode->translateTo('cs') ?>:
            <?php
            $radiusAddition = $parameter->getAdditionByDifficulty();
            $additionStep = $radiusAddition->getAdditionStep();
            $difficultyOfAdditionStep = $radiusAddition->getDifficultyOfAdditionStep();
            $optionRadiusValue = $parameter->getDefaultValue(); // from the lowest
            $previousOptionRadiusValue = null;
            $selectedRadiusValue = $controller->getSelectedModifiersSpellParametersTree()[$treeLevel][$possibleModifierValue][$possibleParameterName] ?? false;
            ?>
            <select name="modifierParameters[<?= $treeLevel ?>][<?= $possibleModifierValue ?>][<?= $possibleParameterName ?>]">
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
<?php } ?>