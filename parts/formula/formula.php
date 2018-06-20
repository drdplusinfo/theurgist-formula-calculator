<?php
namespace DrdPlus\TheurgistCalculator\Formulas;

use DrdPlus\Theurgist\Codes\FormulaCode;

/** @var FormulasController $controller */
$currentFormula = $controller->getCurrentFormula();
$currentFormulaCode = $currentFormula->getFormulaCode();
?>
  <div class="row">
    <div class="col">
      <label for="formula"><strong>Formule</strong>:
      </label>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <select id="formula" name="<?= $controller::FORMULA ?>">
          <?php foreach (FormulaCode::getPossibleValues() as $formulaValue) { ?>
            <option value="<?= $formulaValue ?>"
                    <?php if ($formulaValue === $currentFormulaCode->getValue()){ ?>selected<?php } ?>>
                <?= FormulaCode::getIt($formulaValue)->translateTo('cs') ?>
            </option>
          <?php } ?>
      </select>
      <button type="submit">Vybrat</button>
        <?php $formulaDifficulty = $controller->getFormulasTable()->getFormulaDifficulty($currentFormulaCode); ?>
      <span>[<?= $formulaDifficulty->getValue() ?>]</span>
      <span class="forms" title="Forma">
            <?php $formulaForms = implode(', ', $controller->getFormulaFormNames($currentFormulaCode, 'cs')); ?>
        (<?= $formulaForms ?>)
    </span>
    </div>
  </div>
    <?php
require __DIR__ . '/formula_parameters.php';
require __DIR__ . '/formula_spell_traits.php';
