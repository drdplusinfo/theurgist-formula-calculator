<?php
namespace DrdPlus\TheurgistCalculator\Formulas;

use DrdPlus\Theurgist\Codes\FormulaCode;

/** @var FormulasController $controller */
$currentFormula = $controller->getCurrentFormula();
$currentFormulaCode = $currentFormula->getFormulaCode();
?>
  <div class="block">
    <div class="panel">
            <span class="panel">
                <label><strong>Formule</strong>:
                    <select id="formula" name="<?= $controller::FORMULA ?>">
                        <?php foreach (FormulaCode::getPossibleValues() as $formulaValue) { ?>
                          <option value="<?= $formulaValue ?>"
                                  <?php if ($formulaValue === $currentFormulaCode->getValue()){ ?>selected<?php } ?>>
                                <?= FormulaCode::getIt($formulaValue)->translateTo('cs') ?>
                            </option>
                        <?php } ?>
                    </select>
                </label>
                <button type="submit">Vybrat</button>
                <?php $formulaDifficulty = $controller->getFormulasTable()->getFormulaDifficulty($currentFormulaCode); ?>
            </span>
      <span class="panel">[<?= $formulaDifficulty->getValue() ?>]</span>
    </div>
    <span class="panel forms" title="Forma">
            <?php $formulaForms = implode(', ', $controller->getFormulaFormNames($currentFormulaCode, 'cs')); ?>
      (<?= $formulaForms ?>)
        </span>
  </div>
    <?php
require __DIR__ . '/formula_parameters.php';
require __DIR__ . '/formula_spell_traits.php';
