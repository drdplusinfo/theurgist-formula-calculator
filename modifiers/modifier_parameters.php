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
/** @var bool $modifierIsSelected */

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
            $parameterAddition = $parameter->getAdditionByDifficulty();
            $additionStep = $parameterAddition->getAdditionStep();
            $parameterDifficultyChange = $parameterAddition->getCurrentDifficultyIncrement();
            $optionParameterValue = $parameter->getDefaultValue(); // from the lowest
            $optionParameterChange = 0;
            $previousOptionParameterValue = null;
            $selectedParameterValue = $controller->getSelectedModifiersSpellParametersTree()[$treeLevel][$possibleModifierValue][$possibleParameterName] ?? false;
            ?>
            <select name="modifierParameters[<?= $treeLevel ?>][<?= $possibleModifierValue ?>][<?= $possibleParameterName ?>]"
                    <?php if (!$modifierIsSelected) { ?>disabled<?php } ?>>
                <?php
                do {
                    if ($previousOptionParameterValue === null || $previousOptionParameterValue < $optionParameterValue) { ?>
                        <option value="<?= $optionParameterValue ?>"
                                <?php if ($selectedParameterValue !== false && $selectedParameterValue === $optionParameterValue){ ?>selected<?php } ?>>
                            <?= ($optionParameterValue >= 0 ? '+' : '')
                            . "{$optionParameterValue} [{$parameterDifficultyChange}]"; ?>
                        </option>
                    <?php }
                    $previousOptionParameterValue = $optionParameterValue;
                    $optionParameterValue++;
                    $optionParameterChange++;
                    $parameter = $parameter->getWithAddition($optionParameterChange);
                    $parameterAddition = $parameter->getAdditionByDifficulty();
                    $parameterDifficultyChange = $parameterAddition->getCurrentDifficultyIncrement();
                } while ($additionStep > 0 /* at least once even on no addition possible */ && $parameterDifficultyChange < 21) ?>
            </select>
        </label>
    </div>
<?php } ?>
