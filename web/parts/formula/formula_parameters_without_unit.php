<?php
namespace DrdPlus\Theurgist\Formulas;

use DrdPlus\Codes\Theurgist\FormulaMutableSpellParameterCode;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameter;
use Granam\String\StringTools;

/** @var \DrdPlus\Calculators\Theurgist\FormulaWebPartsContainer $webPartsContainer */

$formulaParametersWithoutUnit = [
    FormulaMutableSpellParameterCode::SPELL_ATTACK,
    FormulaMutableSpellParameterCode::SPELL_BRIGHTNESS,
    FormulaMutableSpellParameterCode::SPELL_POWER,
    FormulaMutableSpellParameterCode::DETAIL_LEVEL,
    FormulaMutableSpellParameterCode::SIZE_CHANGE,
];
$formulasTable = $webPartsContainer->getTables()->getFormulasTable();
foreach ($formulaParametersWithoutUnit as $parameterName) {
    $getParameter = StringTools::assembleGetterForName($parameterName);
    /** @var CastingParameter $parameter */
    $parameter = $formulasTable->$getParameter($webPartsContainer->getCurrentFormulaCode());
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
        $optionParameterValue = $parameter->getDefaultValue(); // from the lowest
        $parameterDifficultyChange = $parameterAdditionByDifficulty->getCurrentDifficultyIncrement();
        $optionParameterChange = 0;
        $previousOptionParameterValue = null;
        $selectedParameterValue = $webPartsContainer->getCurrentFormulaValues()->getCurrentFormulaSpellParameters()[$parameterName] ?? false;
        ?>
      <select name="formula_parameters[<?= $parameterName ?>]">
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
              /** @noinspection PhpUnhandledExceptionInspection */
              $parameter = $parameter->getWithAddition($optionParameterChange);
              $parameterAdditionByDifficulty = $parameter->getAdditionByDifficulty();
              $parameterDifficultyChange = $parameterAdditionByDifficulty->getCurrentDifficultyIncrement();
          } while ($additionStep > 0 /* at least once even on no addition possible */ && $parameterDifficultyChange < 21) ?>
      </select>
    </label>
  </div>
<?php } ?>