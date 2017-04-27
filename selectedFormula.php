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
<?php $formulaSpellTraits = $formulasTable->getSpellTraits($selectedFormula);
$selectedFormulaSpellTraitIndexes = $controller->getSelectedFormulaSpellTraitIndexes();
if (count($formulaSpellTraits) > 0) { ?>
    <div class="block">
        <div class="panel">
            <div>Rysy:</div>
            <?php foreach ($formulaSpellTraits as $formulaSpellTrait) { ?>
                <div class="spell-trait panel">
                    <label>
                        <input type="checkbox" name="formulaSpellTraits[<?= $formulaSpellTrait->getSpellTraitCode() ?>]"
                               value="1"
                               <?php if (in_array($formulaSpellTrait->getSpellTraitCode()->getValue(), $selectedFormulaSpellTraitIndexes, true)) : ?>checked<?php endif ?>>
                        <?= $formulaSpellTrait->getSpellTraitCode()->translateTo('cs') ?>
                        <?php $formulaSpellTrap = $formulaSpellTrait->getTrap($spellTraitsTable);
                        if ($formulaSpellTrap !== null) {
                            echo "({$formulaSpellTrap})";
                        } ?>
                    </label>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>