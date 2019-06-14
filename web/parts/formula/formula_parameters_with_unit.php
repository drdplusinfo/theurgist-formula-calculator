<?php
namespace DrdPlus\Theurgist\Formulas;

use DrdPlus\Codes\Theurgist\FormulaMutableSpellParameterCode;
use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Measurements\Measurement;
use DrdPlus\Tables\Measurements\Speed\Speed;
use DrdPlus\Tables\Measurements\Speed\SpeedBonus;
use DrdPlus\Tables\Measurements\Time\Time;
use DrdPlus\Tables\Measurements\Time\TimeBonus;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameter;
use Granam\String\StringTools;

/** @var \DrdPlus\Calculators\Theurgist\FormulaWebPartsContainer $webPartsContainer */

$formulaParametersWithUnit = [
    FormulaMutableSpellParameterCode::SPELL_DURATION => function ($optionDurationValue) {
        return (new TimeBonus($optionDurationValue, Tables::getIt()->getTimeTable()))->getTime();
    },
    FormulaMutableSpellParameterCode::SPELL_RADIUS => function ($optionRadiusValue) {
        return (new DistanceBonus($optionRadiusValue, Tables::getIt()->getDistanceTable()))->getDistance();
    },
    FormulaMutableSpellParameterCode::SPELL_SPEED => function ($optionSpeedValue) {
        return (new SpeedBonus($optionSpeedValue, Tables::getIt()->getSpeedTable()))->getSpeed();
    },
];
foreach ($formulaParametersWithUnit as $parameterName => $unitFactory) {
    $getParameter = StringTools::assembleGetterForName($parameterName);
    /** @var CastingParameter $parameter */
    $parameter = $webPartsContainer->getTables()->getFormulasTable()->$getParameter($webPartsContainer->getCurrentFormulaCode());
    if ($parameter === null) {
        continue;
    }
    $parameterCode = FormulaMutableSpellParameterCode::getIt($parameterName);
    ?>
  <div class="col">
    <label><?= $parameterCode->translateTo('cs') ?>:
        <?php
        $parameterAdditionByDifficulty = $parameter->getAdditionByDifficulty();
        $additionStep = $parameterAdditionByDifficulty->getAdditionStep();
        $optionParameterChange = 0;
        $parameterDifficultyChange = $parameterAdditionByDifficulty->getCurrentDifficultyIncrement();
        /** @var Measurement $previousOptionParameterValueWithUnit */
        $previousOptionParameterValueWithUnit = null;
        $selectedParameterValue = $webPartsContainer->getCurrentFormulaValues()->getCurrentFormulaSpellParameters()[$parameterName] ?? false;
        ?>
      <select name="formula_parameters[<?= $parameterName ?>]">
          <?php
          do {
              $optionParameterValue = $parameter->getValue(); // from the lowest
              /** @var Distance|Time|Speed $optionValueWithUnit */
              $optionValueWithUnit = $unitFactory($optionParameterValue);
              if (!$previousOptionParameterValueWithUnit
                  || $previousOptionParameterValueWithUnit->getUnit() !== $optionValueWithUnit->getUnit()
                  || $previousOptionParameterValueWithUnit->getValue() < $optionValueWithUnit->getValue()
              ) {
                  $optionUnitInCzech = $optionValueWithUnit->getUnitCode()->translateTo('cs', $optionValueWithUnit->getValue());
                  ?>
                <option value="<?= $optionParameterValue ?>"
                        <?php if ($selectedParameterValue !== false && $selectedParameterValue === $optionParameterValue){ ?>selected<?php } ?>>
                    <?= ($optionParameterValue >= 0 ? '+' : '')
                    . "{$optionParameterValue} ({$optionValueWithUnit->getValue()} {$optionUnitInCzech}) [{$parameterDifficultyChange}]"; ?>
                </option>
              <?php }
              $previousOptionParameterValueWithUnit = $optionValueWithUnit;
              $optionParameterChange++;
              /** @noinspection PhpUnhandledExceptionInspection */
              $parameter = $parameter->getWithAddition($optionParameterChange);
              $parameterAdditionByDifficulty = $parameter->getAdditionByDifficulty();
              $parameterDifficultyChange = $parameterAdditionByDifficulty->getCurrentDifficultyIncrement();
          } while ($additionStep > 0 /* at least once even on no addition possible */ && $parameterDifficultyChange < 21) ?>
      </select>
    </label>
  </div>
<?php } ?>