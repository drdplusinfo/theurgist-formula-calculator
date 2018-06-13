<?php
namespace DrdPlus\Calculator\Theurgist\Formulas;

use DrdPlus\Tables\Tables;
use DrdPlus\Theurgist\Codes\ModifierCode;
use DrdPlus\Theurgist\Codes\ModifierMutableSpellParameterCode;
use DrdPlus\Theurgist\Spells\ModifiersTable;
use DrdPlus\Theurgist\Spells\SpellParameters\EpicenterShift;
use DrdPlus\Theurgist\Spells\SpellParameters\Partials\CastingParameter;
use DrdPlus\Theurgist\Spells\SpellParameters\Radius;
use DrdPlus\Theurgist\Spells\SpellParameters\SpellSpeed;
use Granam\String\StringTools;

/** @var ModifierCode $possibleModifier */
/** @var ModifiersTable $modifiersTable */
/** @var FormulasController $controller */
/** @var int $treeLevel */
/** @var string $possibleModifierValue */
/** @var string $modifiersIndex */
/** @var bool $modifierIsSelected */

$measurementSizes = [
    ModifierMutableSpellParameterCode::SPELL_SPEED => function (SpellSpeed $spellSpeed) {
        $speed = $spellSpeed->getSpeed(Tables::getIt()->getSpeedTable());

        return $speed->getValue() . ' ' . $speed->getUnitCode()->translateTo('cs', $speed->getValue());
    },
    ModifierMutableSpellParameterCode::EPICENTER_SHIFT => function (EpicenterShift $epicenterShift) {
        $distance = $epicenterShift->getDistance(Tables::getIt()->getDistanceTable());

        return $distance->getValue() . ' ' . $distance->getUnitCode()->translateTo('cs', $distance->getValue());
    },
    ModifierMutableSpellParameterCode::RADIUS => function (Radius $radius) {
        $distance = $radius->getDistance(Tables::getIt()->getDistanceTable());

        return $distance->getValue() . ' ' . $distance->getUnitCode()->translateTo('cs', $distance->getValue());
    },
];

foreach (ModifierMutableSpellParameterCode::getPossibleValues() as $possibleParameterName) {
    $getParameter = StringTools::assembleGetterForName($possibleParameterName);
    $parameter = $modifiersTable->$getParameter($possibleModifier);
    if ($parameter === null) {
        continue;
    }
    /** @var CastingParameter $parameter */
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
            $selectedParameterValue = $controller->getCurrentModifiersSpellParametersTree()[$treeLevel][$possibleModifierValue][$possibleParameterName] ?? false;
            ?>
            <select name="<?= $controller::MODIFIER_PARAMETERS ?>[<?= $treeLevel ?>][<?= $possibleModifierValue ?>][<?= $possibleParameterName ?>]"
                    <?php if (!$modifierIsSelected) { ?>disabled<?php } ?>>
                <?php
                do {
                    $optionParameterValue = $parameter->getValue();
                    if ($previousOptionParameterValue === null || $previousOptionParameterValue < $optionParameterValue) { ?>
                        <option value="<?= $optionParameterValue ?>"
                                <?php if ($selectedParameterValue !== false && $selectedParameterValue === $optionParameterValue){ ?>selected<?php } ?>>
                            <?php $parameterValueDescription = ($optionParameterValue >= 0 ? '+' : '') . $optionParameterValue;
                            if (\array_key_exists($possibleParameterName, $measurementSizes)) {
                                $measurementSize = $measurementSizes[$possibleParameterName];
                                $parameterValueDescription .= ' (' . $measurementSize($parameter) . ')';
                            }
                            echo "$parameterValueDescription [{$parameterDifficultyChange}]"; ?>
                        </option>
                    <?php }
                    $previousOptionParameterValue = $optionParameterValue;
                    $optionParameterChange++;
                    /** @noinspection PhpUnhandledExceptionInspection */
                    $parameter = $parameter->getWithAddition($optionParameterChange);
                    $parameterAddition = $parameter->getAdditionByDifficulty();
                    $parameterDifficultyChange = $parameterAddition->getCurrentDifficultyIncrement();
                } while ($additionStep > 0 /* at least once even on no addition possible */ && $parameterDifficultyChange < 21) ?>
            </select>
        </label>
    </div>
<?php } ?>
