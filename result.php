<?php
namespace DrdPlus\Theurgist\Configurator;

/** @var Formula $selectedFormula */
/** @var Controller $controller */
use DrdPlus\Codes\TimeUnitCode;
use DrdPlus\Tables\Tables;
use DrdPlus\Theurgist\Codes\FormulaMutableSpellParameterCode;
use DrdPlus\Theurgist\Spells\Formula;
use DrdPlus\Theurgist\Spells\SpellParameters\RealmsAffection;

?>
    <div>
        Sféra:
        <ol class="realm" start="<?= $selectedFormula->getRequiredRealm() ?>">
            <li></li>
        </ol>
    </div>
    <div>
        Náročnost: <?= $selectedFormula->getCurrentDifficulty()->getValue() ?>
    </div>
    <div>
        <?php
        $realmsAffections = $selectedFormula->getCurrentRealmsAffections();
        if (count($realmsAffections) > 1):?>
            Náklonnosti:
        <?php else: ?>
            Náklonnost:
        <?php endif;
        $inCzech = [];
        /** @var RealmsAffection $realmsAffection */
        foreach ($realmsAffections as $realmsAffection) {
            $inCzech[] = $realmsAffection->getAffectionPeriod()->translateTo('cs') . ' ' . $realmsAffection->getValue();
        }
        echo implode(', ', $inCzech);
        ?>
    </div>
    <div>
        Vyvolání (příprava formule):
        <?php $evocation = $selectedFormula->getCurrentEvocation();
        $evocationTime = $evocation->getEvocationTime(Tables::getIt()->getTimeTable());
        $evocationUnitInCzech = $evocationTime->getUnitCode()->translateTo('cs', $evocationTime->getValue());
        $evocationTimeDescription = ($evocation->getValue() >= 0 ? '+' : '') . $evocation->getValue();
        $evocationTimeDescription .= " ({$evocationTime->getValue()} {$evocationUnitInCzech}";
        if (($evocationTimeInMinutes = $evocationTime->findMinutes()) && $evocationTime->getUnitCode()->getValue() === TimeUnitCode::ROUND) {
            $evocationInMinutesUnitInCzech = $evocationTimeInMinutes->getUnitCode()->translateTo('cs', $evocationTimeInMinutes->getValue());
            $evocationTimeDescription .= '; ' . $evocationTimeInMinutes->getValue() . ' ' . $evocationInMinutesUnitInCzech;
        }
        $evocationTimeDescription .= ')';
        echo $evocationTimeDescription;
        ?>
    </div>
    <div>
        Seslání (vypuštění kouzla):
        <?php $castingRounds = $selectedFormula->getCurrentCastingRounds();
        $castingBonus = $castingRounds->getTime(Tables::getIt()->getTimeTable())->getBonus();
        $casting = $castingBonus->getTime();
        $castingUnitInCzech = $casting->getUnitCode()->translateTo('cs', $casting->getValue());
        echo ($castingBonus->getValue() >= 0 ? '+' : '')
            . "{$castingBonus->getValue()}  ({$casting->getValue()} {$castingUnitInCzech})";
        ?>
    </div>
    <div>
        Doba trvání:
        <?php $duration = $selectedFormula->getCurrentDuration();
        $durationTime = $duration->getDurationTime(Tables::getIt()->getTimeTable());
        $durationUnitInCzech = $durationTime->getUnitCode()->translateTo('cs', $durationTime->getValue());
        echo ($duration->getValue() >= 0 ? '+' : '')
            . "{$duration->getValue()}  ({$durationTime->getValue()} {$durationUnitInCzech})";
        ?>
    </div>
<?php $radius = $selectedFormula->getCurrentRadius();
if ($radius !== null) { ?>
    <div>
        <?= FormulaMutableSpellParameterCode::getIt(FormulaMutableSpellParameterCode::RADIUS)->translateTo('cs') ?>:
        <?php $radiusDistance = $radius->getDistance(Tables::getIt()->getDistanceTable());
        $radiusUnitInCzech = $radiusDistance->getUnitCode()->translateTo('cs', $radiusDistance->getValue());
        echo ($radius->getValue() >= 0 ? '+' : '')
            . "{$radius->getValue()} ({$radiusDistance->getValue()} {$radiusUnitInCzech})";
        ?>
    </div>
<?php }
$power = $selectedFormula->getCurrentPower();
if ($power !== null) { ?>
    <div>
        Síla:
        <?= ($power->getValue() >= 0 ? '+' : '') . $power->getValue(); ?>
    </div>
<?php }
$epicenterShiftOfModified = $selectedFormula->getCurrentEpicenterShift();
if ($epicenterShiftOfModified !== null) {
    $epicenterShiftDistance = $epicenterShiftOfModified->getDistance(Tables::getIt()->getDistanceTable());
    $epicenterShiftUnitInCzech = $epicenterShiftDistance->getUnitCode()->translateTo('cs', $epicenterShiftDistance->getValue());
    ?>
    <div>
        Posun transpozicí:
        <?= ($epicenterShiftOfModified->getValue() >= 0 ? '+' : '') .
        "{$epicenterShiftOfModified->getValue()} ({$epicenterShiftDistance->getValue()} {$epicenterShiftUnitInCzech})" ?>
    </div>
<?php }
$detailLevel = $selectedFormula->getCurrentDetailLevel();
if ($detailLevel !== null) {
    ?>
    <div>
        Detailnost:
        <?= ($detailLevel->getValue() >= 0 ? '+' : '') . $detailLevel->getValue() ?>
    </div>
<?php }
$sizeChange = $selectedFormula->getCurrentSizeChange();
if ($sizeChange !== null) {
    ?>
    <div>
        Změna velikosti:
        <?= ($sizeChange->getValue() >= 0 ? '+' : '') . $sizeChange->getValue() ?>
    </div>
<?php }
$brightness = $selectedFormula->getCurrentBrightness();
if ($brightness !== null) {
    ?>
    <div>
        Jas:
        <?= ($brightness->getValue() >= 0 ? '+' : '') . $brightness->getValue() ?>
    </div>
<?php }
$spellSpeed = $selectedFormula->getCurrentSpellSpeed();
if ($spellSpeed !== null) {
    $speed = $spellSpeed->getSpeed(Tables::getIt()->getSpeedTable());
    $spellSpeedUnitInCzech = $speed->getUnitCode()->translateTo('cs', $speed->getValue());
    ?>
    <div>
        Rychlost:
        <?= ($spellSpeed->getValue() >= 0 ? '+' : '') .
        "{$spellSpeed->getValue()} ({$speed->getValue()} {$spellSpeedUnitInCzech})" ?>
    </div>
<?php }
$attack = $selectedFormula->getCurrentAttack();
if ($attack !== null) { ?>
    <div>
        Útočnost: <?= ($attack->getValue() >= 0 ? '+' : '') . $attack->getValue(); ?>
    </div>
<?php }