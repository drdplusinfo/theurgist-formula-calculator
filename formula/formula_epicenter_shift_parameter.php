<?php
namespace DrdPlus\Theurgist\Configurator;

use DrdPlus\Tables\Tables;
use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Spells\FormulasTable;

/** @var FormulaCode $selectedFormulaCode */
/** @var FormulasTable $formulasTable */

$epicenterShift = $formulasTable->getEpicenterShift($selectedFormulaCode);
if ($epicenterShift === null) {
    return;
}
// formula itself can not shift epicenter so no options here
$epicenterShiftDistance = $epicenterShift->getDistance(Tables::getIt()->getDistanceTable());
$epicenterShiftUnitInCzech = $epicenterShiftDistance->getUnitCode()->translateTo('cs', $epicenterShiftDistance->getValue());
?>
<div class="parameter panel">
    posun transpozicí:
    <?= ($epicenterShift->getValue() >= 0 ? '+' : '') .
    "{$epicenterShift->getValue()} ({$epicenterShiftDistance->getValue()} {$epicenterShiftUnitInCzech})" ?>
</div>