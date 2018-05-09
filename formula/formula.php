<?php
namespace DrdPlus\Calculator\Theurgist\Formulas;

use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Spells\FormulasTable;
use DrdPlus\Theurgist\Spells\SpellTraitsTable;

/** @var FormulaCode $selectedFormulaCode */
/** @var Controller $controller */
/** @var FormulasTable $formulasTable */
/** @var SpellTraitsTable $spellTraitsTable */
?>
    <div class="block">
        <div class="panel">
            <span class="panel">
                <label><strong>Formule</strong>:
                    <select id="formula" name="<?= $controller::FORMULA ?>">
                        <?php foreach (FormulaCode::getPossibleValues() as $formulaValue) { ?>
                            <option value="<?= $formulaValue ?>"
                                    <?php if ($formulaValue === $selectedFormulaCode->getValue()){ ?>selected<?php } ?>>
                                <?= FormulaCode::getIt($formulaValue)->translateTo('cs') ?>
                            </option>
                        <?php } ?>
                    </select>
                </label>
                <button type="submit">Vybrat</button>
                <?php $formulaDifficulty = $formulasTable->getFormulaDifficulty($selectedFormulaCode); ?>
            </span>
            <span class="panel">[<?= $formulaDifficulty->getValue() ?>]</span>
        </div>
        <span class="panel forms" title="Forma">
            <?php $formulaForms = implode(', ', $controller->getFormulaFormNames($selectedFormulaCode, 'cs')); ?>
            (<?= $formulaForms ?>)
        </span>
    </div>
    <?php
require __DIR__ . '/formula_parameters.php';
require __DIR__ . '/formula_spell_traits.php';
