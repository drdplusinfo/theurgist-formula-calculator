<?php
namespace DrdPlus\Calculators\Theurgist\Web;

use DrdPlus\Calculators\Theurgist\CurrentFormulaValues;
use DrdPlus\Codes\Theurgist\FormulaCode;

/** @var \DrdPlus\Calculators\Theurgist\FormulaWebPartsContainer $webPartsContainer */
?>
  <div class="row">
    <div class="col">
      <label for="formula"><strong>Formule</strong>:
      </label>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <select id="formula" name="<?= CurrentFormulaValues::FORMULA ?>">
          <?php foreach (FormulaCode::getPossibleValues() as $formulaValue) { ?>
            <option value="<?= $formulaValue ?>"
                    <?php if ($formulaValue === $webPartsContainer->getCurrentFormulaCode()->getValue()){ ?>selected<?php } ?>>
                <?= FormulaCode::getIt($formulaValue)->translateTo('cs') ?>
            </option>
          <?php } ?>
      </select>
      <button type="submit">Vybrat</button>
        <?php $formulaDifficulty = $webPartsContainer->getTables()->getFormulasTable()->getFormulaDifficulty($webPartsContainer->getCurrentFormulaCode()); ?>
      <span>[<?= $formulaDifficulty->getValue() ?>]</span>
      <span class="forms" title="Forma">
            <?php $formulaForms = implode(', ', $webPartsContainer->getCurrentFormulaValues()->getFormulaFormNames($webPartsContainer->getCurrentFormulaCode(), 'cs')); ?>
        (<?= $formulaForms ?>)
    </span>
    </div>
  </div>
    <?php
require __DIR__ . '/formula_parameters.php';
require __DIR__ . '/formula_spell_traits.php';
