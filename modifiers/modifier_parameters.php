<?php
namespace DrdPlus\Theurgist\Configurator;

use DrdPlus\Tables\Tables;
use DrdPlus\Theurgist\Codes\ModifierCode;
use DrdPlus\Theurgist\Codes\ModifierMutableSpellParameterCode;
use DrdPlus\Theurgist\Spells\ModifiersTable;
use DrdPlus\Theurgist\Spells\SpellParameters\Partials\IntegerCastingParameter;
use DrdPlus\Theurgist\Spells\SpellParameters\SpellSpeed;
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
            $optionParameterChange = 0;
            $previousOptionParameterValue = null;
            $selectedParameterValue = $controller->getSelectedModifiersSpellParametersTree()[$treeLevel][$possibleModifierValue][$possibleParameterName] ?? false;
            ?>
            <select name="modifierParameters[<?= $treeLevel ?>][<?= $possibleModifierValue ?>][<?= $possibleParameterName ?>]"
                    <?php if (!$modifierIsSelected) { ?>disabled<?php } ?>>
                <?php
                do {
                    $optionParameterValue = $parameter->getValue();
                    if ($previousOptionParameterValue === null || $previousOptionParameterValue < $optionParameterValue) { ?>
                        <option value="<?= $optionParameterValue ?>"
                                <?php if ($selectedParameterValue !== false && $selectedParameterValue === $optionParameterValue){ ?>selected<?php } ?>>
                            <?php $parameterValueDescription = ($optionParameterValue >= 0 ? '+' : '') . $optionParameterValue;
                            if ($possibleParameterName === ModifierMutableSpellParameterCode::SPELL_SPEED) {
                                /** @var SpellSpeed $parameter */
                                $speed = $parameter->getSpeed(Tables::getIt()->getSpeedTable());
                                $speedDescription = $speed->getValue() . ' ' . $speed->getUnitCode()->translateTo('cs', $speed->getValue());
                                $parameterValueDescription .= ' (' . $speedDescription . ')';
                            }
                            echo "$parameterValueDescription [{$parameterDifficultyChange}]"; ?>
                        </option>
                    <?php }
                    $previousOptionParameterValue = $optionParameterValue;
                    $optionParameterChange++;
                    $parameter = $parameter->getWithAddition($optionParameterChange);
                    $parameterAddition = $parameter->getAdditionByDifficulty();
                    $parameterDifficultyChange = $parameterAddition->getCurrentDifficultyIncrement();
                } while ($additionStep > 0 /* at least once even on no addition possible */ && $parameterDifficultyChange < 21) ?>
            </select>
        </label>
    </div>
<?php } ?>
