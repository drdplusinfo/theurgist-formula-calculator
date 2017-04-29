<?php
namespace DrdPlus\Theurgist\Configurator;

use DrdPlus\Tables\Tables;
use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Formulas\FormulasTable;

/** @var FormulaCode $selectedFormula */
/** @var FormulasTable $formulasTable */
/** @var IndexController $controller */

?>
<div class="block">
    <span class="panel">Parametry</span>
    <div class="attribute panel">
        Doba trvání:
        <?php
        $duration = $formulasTable->getDuration($selectedFormula);
        $durationTime = $duration->getDurationTime(Tables::getIt()->getTimeTable());
        $durationUnitInCzech = $durationTime->getUnitCode()->translateTo('cs', $durationTime->getValue());
        echo ($duration->getValue() >= 0 ? '+' : '')
            . "{$duration->getValue()}  ({$durationTime->getValue()} {$durationUnitInCzech})";
        ?>
    </div>
    <?php $radius = $formulasTable->getRadius($selectedFormula);
    if ($radius !== null) { ?>
        <div class="attribute panel">
            Poloměr:
            <?php $radiusDistance = $radius->getDistance(Tables::getIt()->getDistanceTable());
            $radiusUnitInCzech = $radiusDistance->getUnitCode()->translateTo('cs', $radiusDistance->getValue());
            echo ($radius->getValue() >= 0 ? '+' : '')
                . "{$radius->getValue()} ({$radiusDistance->getValue()} {$radiusUnitInCzech})";
            ?>
        </div>
    <?php }
    $power = $formulasTable->getPower($selectedFormula);
    if ($power !== null) { ?>
        <div class="attribute panel">
            Síla:
            <?= ($power->getValue() >= 0 ? '+' : '') . $power->getValue(); ?>
        </div>
    <?php }
    $epicenterShift = $formulasTable->getEpicenterShift($selectedFormula);
    if ($epicenterShift !== null) {
        $epicenterShiftDistance = $epicenterShift->getDistance(Tables::getIt()->getDistanceTable());
        $epicenterShiftUnitInCzech = $epicenterShiftDistance->getUnitCode()->translateTo('cs', $epicenterShiftDistance->getValue());
        ?>
        <div class="attribute panel">
            Posun transpozicí:
            <?= ($epicenterShift->getValue() >= 0 ? '+' : '') .
            "{$epicenterShift->getValue()} ({$epicenterShiftDistance->getValue()} {$epicenterShiftUnitInCzech})" ?>
        </div>
    <?php }
    $detailLevel = $formulasTable->getDetailLevel($selectedFormula);
    if ($detailLevel !== null) {
        ?>
        <div class="attribute panel">
            Detailnost:
            <?= ($detailLevel->getValue() >= 0 ? '+' : '') . $detailLevel->getValue() ?>
        </div>
    <?php }
    $sizeChange = $formulasTable->getSizeChange($selectedFormula);
    if ($sizeChange !== null) {
        ?>
        <div class="attribute panel">
            Změna velikosti:
            <?= ($sizeChange->getValue() >= 0 ? '+' : '') . $sizeChange->getValue() ?>
        </div>
    <?php }
    $brightness = $formulasTable->getBrightness($selectedFormula);
    if ($brightness !== null) {
        ?>
        <div class="attribute panel">
            Jas:
            <?= ($brightness->getValue() >= 0 ? '+' : '') . $brightness->getValue() ?>
        </div>
    <?php }
    $spellSpeed = $formulasTable->getSpellSpeed($selectedFormula);
    if ($spellSpeed !== null) {
        $spellSpeed = $spellSpeed->getSpeed(Tables::getIt()->getSpeedTable());
        $spellSpeedUnitInCzech = $spellSpeed->getUnitCode()->translateTo('cs', $spellSpeed->getValue());
        ?>
        <div class="attribute panel">
            Rychlost:
            <?= ($spellSpeed->getValue() >= 0 ? '+' : '') .
            "{$spellSpeed->getValue()} ({$spellSpeed->getValue()} {$spellSpeedUnitInCzech})" ?>
        </div>
    <?php }
    $attack = $formulasTable->getAttack($selectedFormula);
    if ($attack !== null) { ?>
        <div class="attribute panel">
            Útočnost: <?= ($attack->getValue() >= 0 ? '+' : '') . $attack->getValue(); ?>
        </div>
    <?php } ?>
</div>