<?php
namespace DrdPlus\Theurgist\Configurator;

use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Formulas\FormulasTable;

/** @var FormulaCode $selectedFormula */
/** @var IndexController $controller */
/** @var FormulasTable $formulasTable */
?>
    <div class="block">
        <div class="panel">
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
        </div>
        <span class="panel forms" title="Forma">
            <?php $formulaForms = implode(', ', $controller->getFormulaFormNames($selectedFormula, 'cs')); ?>
            (<?= $formulaForms ?>)
        </span>
    </div>
<?php $spellTraits = $formulasTable->getSpellTraits($selectedFormula);
$selectedSpellTraitIndexes = $controller->getSelectedSpellTraitIndexes();
if (count($spellTraits) > 0) { ?>
    <div class="block">
        <div class="panel">
            <span class="panel">Rysy:</span>
            <?php foreach ($spellTraits as $spellTrait) { ?>
                <div class="spell-trait panel">
                    <label>
                        <input type="checkbox" name="spellTraits[<?= $spellTrait->getSpellTraitCode() ?>]" value="1"
                               <?php if (in_array($spellTrait->getSpellTraitCode()->getValue(), $selectedSpellTraitIndexes, true)) : ?>checked<?php endif ?>>
                        <?= $spellTrait->getSpellTraitCode()->translateTo('cs') ?>
                    </label>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>