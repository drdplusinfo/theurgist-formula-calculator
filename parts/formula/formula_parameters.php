<?php
namespace DrdPlus\Calculator\Theurgist\Formulas;

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