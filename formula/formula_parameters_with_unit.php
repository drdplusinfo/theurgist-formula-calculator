<?php
namespace DrdPlus\Calculator\Theurgist\Formulas;

use DrdPlus\Properties\Derived\Speed;
use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Measurements\Measurement;
use DrdPlus\Tables\Measurements\Speed\SpeedBonus;
use DrdPlus\Tables\Measurements\Time\Time;
use DrdPlus\Tables\Measurements\Time\TimeBonus;
use DrdPlus\Tables\Tables;
use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Codes\FormulaMutableSpellParameterCode;
use DrdPlus\Theurgist\Spells\FormulasTable;
use DrdPlus\Theurgist\Spells\SpellParameters\Partials\CastingParameter;
use Granam\String\StringTools;

/** @var FormulaCode $selectedFormulaCode */
/** @var FormulasTable $formulasTable */
/** @var Controller $controller */

$formulaParametersWithoutUnit = [
    FormulaMutableSpellParameterCode::DURATION => function ($optionDurationValue) {
        return (new TimeBonus($optionDurationValue, Tables::getIt()->getTimeTable()))->getTime();
    },
    FormulaMutableSpellParameterCode::RADIUS => function ($optionDurationValue) {
        return (new DistanceBonus($optionDurationValue, Tables::getIt()->getDistanceTable()))->getDistance();
    },
    FormulaMutableSpellParameterCode::SPELL_SPEED => function ($optionDurationValue) {
        return (new SpeedBonus($optionDurationValue, Tables::getIt()->getSpeedTable()))->getSpeed();
    },
];
foreach ($formulaParametersWithoutUnit as $parameterName => $unitFactory) {
    $getParameter = StringTools::assembleGetterForName($parameterName);
    /** @var CastingParameter $parameter */
    $parameter = $formulasTable->$getParameter($selectedFormulaCode);
    if ($parameter === null) {
        continue;
    }
    $parameterCode = FormulaMutableSpellParameterCode::getIt($parameterName);
    ?>
    <div class="parameter panel">
        <label><?= $parameterCode->translateTo('cs') ?>:
            <?php
            $parameterAdditionByDifficulty = $parameter->getAdditionByDifficulty();
            $additionStep = $parameterAdditionByDifficulty->getAdditionStep();
            $optionParameterChange = 0;
            $parameterDifficultyChange = $parameterAdditionByDifficulty->getCurrentDifficultyIncrement();
            /** @var Measurement $previousOptionParameterValueWithUnit */
            $previousOptionParameterValueWithUnit = null;
            $selectedParameterValue = $controller->getCurrentFormulaSpellParameters()[$parameterName] ?? false;
            ?>
            <select name="<?= $controller::FORMULA_PARAMETERS ?>[<?= $parameterName ?>]">
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