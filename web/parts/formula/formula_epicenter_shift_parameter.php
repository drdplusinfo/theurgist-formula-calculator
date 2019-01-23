<?php
namespace DrdPlus\Theurgist\Formulas;

use DrdPlus\Calculators\Theurgist\CurrentFormulaValues;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Spells\FormulasTable;

/** @var FormulasTable $formulasTable */
/** @var CurrentFormulaValues $currentFormulaValues */

$epicenterShift = $formulasTable->getEpicenterShift($currentFormulaValues->getCurrentFormulaCode());
if ($epicenterShift === null) {
    return;
}
// formula itself can not shift epicenter so no options here
$epicenterShiftDistance = $epicenterShift->getDistance(Tables::getIt()->getDistanceTable());
$epicenterShiftUnitInCzech = $epicenterShiftDistance->getUnitCode()->translateTo('cs', $epicenterShiftDistance->getValue());
?>
<div class="col">
  posun transpozicí:
    <?= ($epicenterShift->getValue() >= 0 ? '+' : '') .
    "{$epicenterShift->getValue()} ({$epicenterShiftDistance->getValue()} {$epicenterShiftUnitInCzech})" ?>
</div>