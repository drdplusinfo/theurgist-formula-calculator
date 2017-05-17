<?php
namespace DrdPlus\Theurgist\Configurator;

use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Spells\FormulasTable;

/** @var FormulaCode $selectedFormulaCode */
/** @var FormulasTable $formulasTable */
/** @var IndexController $controller */

?>
<div class="block">
    <span class="panel">Parametry</span>
    <?php
    include __DIR__ . '/formula_duration_parameter.php';
    include __DIR__ . '/formula_radius_parameter.php';
    include __DIR__ . '/formula_power_parameter.php';
    include __DIR__ . '/formula_epicenter_shift_parameter.php';
    include __DIR__ . '/formula_detail_level_parameter.php';
    include __DIR__ . '/formula_size_change_parameter.php';
    include __DIR__ . '/formula_brightness_parameter.php';
    include __DIR__ . '/formula_spell_speed_parameter.php';
    include __DIR__ . '/formula_attack_parameter.php';
    ?>
</div>