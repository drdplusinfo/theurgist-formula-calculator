<?php
namespace DrdPlus\Theurgist\Formulas;

use DrdPlus\Calculators\Theurgist\CurrentFormulaValues;
use DrdPlus\Codes\Theurgist\ModifierCode;
use DrdPlus\Codes\Theurgist\ModifierMutableSpellParameterCode;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\EpicenterShift;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameter;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellRadius;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellSpeed;
use Granam\String\StringTools;

/** @var ModifierCode $possibleModifier */
/** @var Tables $tables */
/** @var int $treeLevel */
/** @var string $possibleModifierValue */
/** @var string $modifiersIndex */
/** @var bool $modifierIsSelected */
/** @var CurrentFormulaValues $currentFormulaValues */

$measurementSizes = [
    ModifierMutableSpellParameterCode::SPELL_SPEED => function (SpellSpeed $spellSpeed) {
        $speed = $spellSpeed->getSpeedBonus()->getSpeed();
        return $speed->getValue() . ' ' . $speed->getUnitCode()->translateTo('cs', $speed->getValue());
    },
    ModifierMutableSpellParameterCode::EPICENTER_SHIFT => function (EpicenterShift $epicenterShift) {
        $distance = $epicenterShift->getDistance();
        return $distance->getValue() . ' ' . $distance->getUnitCode()->translateTo('cs', $distance->getValue());
    },
    ModifierMutableSpellParameterCode::SPELL_RADIUS => function (SpellRadius $spellRadius) {
        $distance = $spellRadius->getDistanceBonus()->getDistance();
        return $distance->getValue() . ' ' . $distance->getUnitCode()->translateTo('cs', $distance->getValue());
    },
];

$modifiersTable = $tables->getModifiersTable();
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
        $selectedParameterValue = $currentFormulaValues->getCurrentModifiersSpellParametersTree()[$treeLevel][$possibleModifierValue][$possibleParameterName] ?? false;
        ?>
      <select name="modifier_parameters[<?= $treeLevel ?>][<?= $possibleModifierValue ?>][<?= $possibleParameterName ?>]"
              <?php if (!$modifierIsSelected) { ?>disabled<?php } ?>>
          <?php
          do {
              $optionParameterValue = $parameter->getValue();
              if ($previousOptionParameterValue === null || $previousOptionParameterValue < $optionParameterValue) { ?>
                <option value="<?= $optionParameterValue ?>"
                        <?php if ($selectedParameterValue !== false && $selectedParameterValue === $optionParameterValue){ ?>selected<?php } ?>>
                    <?php $parameterValueDescription = ($optionParameterValue >= 0 ? '+' : '') . $optionParameterValue;
                    if (array_key_exists($possibleParameterName, $measurementSizes)) {
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
