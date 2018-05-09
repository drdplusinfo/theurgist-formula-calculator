<?php
namespace DrdPlus\Calculator\Theurgist\Formulas;

use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Spells\FormulasTable;

/** @var FormulaCode $selectedFormulaCode */
/** @var FormulasTable $formulasTable */
/** @var Controller $controller */

?>
<div class="block">
    <span class="panel"><strong>Parametry</strong>:</span>
    <?php
    include __DIR__ . '/formula_parameters_without_unit.php';
    include __DIR__ . '/formula_parameters_with_unit.php';
    include __DIR__ . '/formula_epicenter_shift_parameter.php';
    ?>
</div>