<?php
namespace DrdPlus\Theurgist\Configurator;

/** @var FormulaCode $selectedFormula */
/** @var FormulasTable $formulasTable */
/** @var IndexController $controller */
use DrdPlus\Codes\TimeUnitCode;
use DrdPlus\Tables\Tables;
use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Formulas\CastingParameters\Affection;
use DrdPlus\Theurgist\Formulas\FormulasTable;

$selectedModifiers = $controller->getSelectedModifierCodes();
$selectedSpellTraits = $controller->getSelectedSpellTraitCodes();
?>
    <div>
        Sféra:
        <?php
        $realmOfModified = $formulasTable->getRealmOfModified($selectedFormula, $selectedModifiers, $selectedSpellTraits); ?>
        <ol class="realm" start="<?= $realmOfModified->getValue() ?>">
            <li></li>
        </ol>
    </div>
    <div>
        Náročnost: <?= $formulasTable->getDifficultyOfModified($selectedFormula, $selectedModifiers, $selectedSpellTraits)->getValue() ?>
    </div>
    <div>
        <?php
        $affectionsOfModified = $formulasTable->getAffectionsOfModified($selectedFormula, $selectedModifiers, $selectedSpellTraits);
        if (count($affectionsOfModified) > 1):?>
            Náklonnosti:
        <?php else: ?>
            Náklonnost:
        <?php endif;
        $inCzech = [];
        /** @var Affection $affectionOfModified */
        foreach ($affectionsOfModified as $affectionOfModified) {
            $inCzech[] = $affectionOfModified->getAffectionPeriod()->translateTo('cs') . ' ' . $affectionOfModified->getValue();
        }
        echo implode(', ', $inCzech);
        ?>
    </div>
    <div>
        Vyvolání (příprava formule):
        <?php $evocationOfModified = $formulasTable->getEvocationOfModified($selectedFormula, $selectedModifiers, $selectedSpellTraits);
        $evocationTime = $evocationOfModified->getEvocationTime(Tables::getIt()->getTimeTable());
        $evocationUnitInCzech = $evocationTime->getUnitCode()->translateTo('cs', $evocationTime->getValue());
        $evocationTimeDescription = ($evocationOfModified->getValue() >= 0 ? '+' : '') . $evocationOfModified->getValue();
        $evocationTimeDescription .= " ({$evocationTime->getValue()} {$evocationUnitInCzech}";
        if (($evocationTimeInMinutes = $evocationTime->findMinutes()) && $evocationTime->getUnitCode()->getValue() === TimeUnitCode::ROUND) {
            $evocationInMinutesUnitInCzech = $evocationTimeInMinutes->getUnitCode()->translateTo('cs', $evocationTimeInMinutes->getValue());
            $evocationTimeDescription .= ', ' . $evocationTimeInMinutes->getValue() . ' ' . $evocationInMinutesUnitInCzech;
        }
        $evocationTimeDescription .= ')';
        echo $evocationTimeDescription;
        ?>
    </div>
    <div>
        Seslání (vypuštění kouzla):
        <?php $castingOfModified = $formulasTable->getCastingOfModified($selectedFormula, $selectedModifiers, $selectedSpellTraits);
        $castingBonus = $castingOfModified->getBonus();
        $castingUnitInCzech = $castingOfModified->getUnitCode()->translateTo('cs', $castingOfModified->getValue());
        echo ($castingBonus->getValue() >= 0 ? '+' : '')
            . "{$castingBonus->getValue()}  ({$castingOfModified->getValue()} {$castingUnitInCzech})";
        ?>
    </div>
    <div>
        Doba trvání:
        <?php $durationOfModified = $formulasTable->getDurationOfModified($selectedFormula, $selectedModifiers, $selectedSpellTraits);
        $durationTime = $durationOfModified->getDurationTime(Tables::getIt()->getTimeTable());
        $durationUnitInCzech = $durationTime->getUnitCode()->translateTo('cs', $durationTime->getValue());
        echo ($durationOfModified->getValue() >= 0 ? '+' : '')
            . "{$durationOfModified->getValue()}  ({$durationTime->getValue()} {$durationUnitInCzech})";
        ?>
    </div>
<?php $radiusOfModified = $formulasTable->getRadiusOfModified($selectedFormula, $selectedModifiers, $selectedSpellTraits);
if ($radiusOfModified !== null) { ?>
    <div>
        Poloměr:
        <?php $radiusDistance = $radiusOfModified->getDistance(Tables::getIt()->getDistanceTable());
        $radiusUnitInCzech = $radiusDistance->getUnitCode()->translateTo('cs', $radiusDistance->getValue());
        echo ($radiusOfModified->getValue() >= 0 ? '+' : '')
            . "{$radiusOfModified->getValue()} ({$radiusDistance->getValue()} {$radiusUnitInCzech})";
        ?>
    </div>
<?php }
$powerOfModified = $formulasTable->getPowerOfModified($selectedFormula, $selectedModifiers, $selectedSpellTraits);
if ($powerOfModified !== null) { ?>
    <div>
        Síla:
        <?= ($powerOfModified->getValue() >= 0 ? '+' : '') . $powerOfModified->getValue(); ?>
    </div>
<?php }
$epicenterShiftOfModified = $formulasTable->getEpicenterShiftOfModified($selectedFormula, $selectedModifiers, $selectedSpellTraits);
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
$detailLevelOfModified = $formulasTable->getDetailLevelOfModified($selectedFormula, $selectedModifiers, $selectedSpellTraits);
if ($detailLevelOfModified !== null) {
    ?>
    <div>
        Detailnost:
        <?= ($detailLevelOfModified->getValue() >= 0 ? '+' : '') . $detailLevelOfModified->getValue() ?>
    </div>
<?php }
$sizeChangeOfModified = $formulasTable->getSizeChangeOfModified($selectedFormula, $selectedModifiers, $selectedSpellTraits);
if ($sizeChangeOfModified !== null) {
    ?>
    <div>
        Změna velikosti:
        <?= ($sizeChangeOfModified->getValue() >= 0 ? '+' : '') . $sizeChangeOfModified->getValue() ?>
    </div>
<?php }
$brightnessOfModified = $formulasTable->getBrightnessOfModified($selectedFormula, $selectedModifiers, $selectedSpellTraits);
if ($brightnessOfModified !== null) {
    ?>
    <div>
        Jas:
        <?= ($brightnessOfModified->getValue() >= 0 ? '+' : '') . $brightnessOfModified->getValue() ?>
    </div>
<?php }
$spellSpeedOfModified = $formulasTable->getSpellSpeedOfModified($selectedFormula, $selectedModifiers, $selectedSpellTraits);
if ($spellSpeedOfModified !== null) {
    $spellSpeed = $spellSpeedOfModified->getSpeed(Tables::getIt()->getSpeedTable());
    $spellSpeedUnitInCzech = $spellSpeed->getUnitCode()->translateTo('cs', $spellSpeed->getValue());
    ?>
    <div>
        Rychlost:
        <?= ($spellSpeedOfModified->getValue() >= 0 ? '+' : '') .
        "{$spellSpeedOfModified->getValue()} ({$spellSpeed->getValue()} {$spellSpeedUnitInCzech})" ?>
    </div>
<?php }
$attackOfModified = $formulasTable->getAttackOfModified($selectedFormula, $selectedModifiers, $selectedSpellTraits);
if ($attackOfModified !== null) { ?>
    <div>
        Útočnost: <?= ($attackOfModified->getValue() >= 0 ? '+' : '') . $attackOfModified->getValue(); ?>
    </div>
<?php }