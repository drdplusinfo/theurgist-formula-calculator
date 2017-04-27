<?php
namespace DrdPlus\Theurgist\Configurator;

use DrdPlus\Theurgist\Codes\FormulaCode;

/** @var FormulaCode $selectedFormula */
/** @var IndexController $controller */
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
    <span class="panel forms">
        <?php $formulaForms = implode(', ', $controller->getFormulaFormNames($selectedFormula, 'cs')); ?>
        (<?= $formulaForms ?> )
    </span>
</div>