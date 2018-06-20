<?php
namespace DrdPlus\TheurgistCalculator\Formulas;

use DrdPlus\Tables\Tables;

/** @var FormulasController $controller */

$epicenterShift = $controller->getFormulasTable()->getEpicenterShift($controller->getCurrentFormula()->getFormulaCode());
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