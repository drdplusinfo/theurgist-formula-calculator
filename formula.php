<?php
namespace DrdPlus\Theurgist\Configurator;

use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Formulas\FormulasTable;
use DrdPlus\Theurgist\Formulas\SpellTraitsTable;

/** @var FormulaCode $selectedFormula */
/** @var IndexController $controller */
/** @var FormulasTable $formulasTable */
/** @var SpellTraitsTable $spellTraitsTable */
?>
    <div class="block">
        <div class="panel">
            <span class="panel">
                <label>Formule:
                    <select id="formula" name="formula">
                        <?php foreach (FormulaCode::getPossibleValues() as $formulaValue) { ?>
                            <option value="<?= $formulaValue ?>"
                                    <?php if ($formulaValue === $selectedFormula->getValue()): ?>selected<?php endif ?>>
                                <?= FormulaCode::getIt($formulaValue)->translateTo('cs') ?>
                            </option>
                        <?php } ?>
                    </select>
                </label>
                <button type="submit">Vybrat</button>
                <?php $formulaDifficulty = $formulasTable->getDifficulty($selectedFormula); ?>
            </span>
            <span class="panel"><?= ($formulaDifficulty->getValue() > 0 ? '+' : '') . $formulaDifficulty ?></span>
        </div>
        <span class="panel forms" title="Forma">
            <?php $formulaForms = implode(', ', $controller->getFormulaFormNames($selectedFormula, 'cs')); ?>
            (<?= $formulaForms ?>)
        </span>
    </div>
<?php
require __DIR__ . '/formula_parameters.php';
require __DIR__ . '/formula_spell_traits.php';
