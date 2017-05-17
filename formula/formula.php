<?php
namespace DrdPlus\Theurgist\Configurator;

use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Spells\FormulasTable;
use DrdPlus\Theurgist\Spells\SpellTraitsTable;

/** @var FormulaCode $selectedFormulaCode */
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
                                    <?php if ($formulaValue === $selectedFormulaCode->getValue()){ ?>selected="selected"<?php } ?>>
                                <?= FormulaCode::getIt($formulaValue)->translateTo('cs') ?>
                            </option>
                        <?php } ?>
                    </select>
                </label>
                <button type="submit">Vybrat</button>
                <?php $formulaDifficulty = $formulasTable->getFormulaDifficulty($selectedFormulaCode); ?>
            </span>
            <span class="panel"><?= ($formulaDifficulty->getValue() > 0 ? '+' : '') . $formulaDifficulty ?></span>
        </div>
        <span class="panel forms" title="Forma">
            <?php $formulaForms = implode(', ', $controller->getFormulaFormNames($selectedFormulaCode, 'cs')); ?>
            (<?= $formulaForms ?>)
        </span>
    </div>
    <?php
require __DIR__ . '/formula_parameters.php';
require __DIR__ . '/formula_spell_traits.php';
