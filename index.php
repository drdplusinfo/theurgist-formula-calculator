<?php
namespace DrdPlus\Theurgist\Configurator;

use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Codes\ModifierCode;
use DrdPlus\Theurgist\Formulas\FormulasTable;
use DrdPlus\Theurgist\Formulas\ModifiersTable;
use DrdPlus\Theurgist\Formulas\ProfilesTable;

require_once __DIR__ . '/vendor/autoload.php';

error_reporting(-1);
ini_set('display_errors', '1');

$formulasTable = new FormulasTable();
$selectedFormula = $_GET['formula'] ?? false;
$previouslySelectedFormula = $_GET['previousFormula'] ?? false;
$formulaModifiers = [];
if ($selectedFormula === $previouslySelectedFormula && !empty($_GET['directModifiers'])) {
    foreach ((array)$_GET['directModifiers'] as $directModifier) {
        $formulaModifiers[$directModifier] = ModifierCode::getIt($directModifier);
    }
}
$modifiersOfModifiers = [];
$modifiersTable = new ModifiersTable();
$profilesTable = new ProfilesTable();
if (count($formulaModifiers) > 0) {
    $currentModifiers = [];
    $modifiersOfModifiers[] = &$currentModifiers;
    foreach ($formulaModifiers as $formulaModifierValue => $formulaModifier) {
        $currentModifiers[$formulaModifierValue] = [];
        foreach ($modifiersTable->getProfiles($formulaModifier) as $profileCode) {
            foreach ($profilesTable->getModifiersForProfile($profileCode) as $modifierForProfile) {
                $currentModifiers[$formulaModifierValue][$modifierForProfile->getValue()] = $modifierForProfile;
            }
        }
    }
    unset($currentModifiers);
}
?>
<!DOCTYPE html>
<html lang="cs" xmlns="http://www.w3.org/1999/html">
<head>
    <title>Formule pro DrD+ theurga</title>
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<form id="configurator" method="get">
    <input type="hidden" name="previousFormula" value="<?= $selectedFormula ?>">
    <div>
        <label>Formule:
            <select id="formula" name="formula">
                <?php
                foreach (FormulaCode::getPossibleValues() as $formulaValue) {
                    ?>
                    <option value="<?= $formulaValue ?>"
                            <?php if ($formulaValue === $selectedFormula): ?>selected<?php endif ?>>
                        <?= FormulaCode::getIt($formulaValue)->translateTo('cs') ?>
                    </option>
                <?php } ?>
            </select>
        </label>
        <button type="submit">Vybrat formuli</button>
    </div>
    <?php if ($selectedFormula !== false) {
        ?>
        <div id="directModifiers">
            Přímé modifikátory:
            <?php
            foreach ($formulasTable->getModifiers(FormulaCode::getIt($selectedFormula)) as $modifierCode) { ?>
                <label>
                    <input name="directModifiers[]" type="checkbox" value="<?= $modifierCode ?>"
                           <?php if (array_key_exists($modifierCode->getValue(), $formulaModifiers)): ?>checked<?php endif ?>>
                    <?= $modifierCode->translateTo('cs') ?>
                </label>
            <?php } ?>
            <button type="submit">Vybrat přímé modifikátory</button>
        </div>
        <?php foreach ($modifiersOfModifiers as $tier => $tierModifiers) { ?>
            <div id="modifiersOfModifiers<?= $tier ?>">
                <?php foreach ($tierModifiers as $modifiedModifierValue => $modifierModifiers) {
                    foreach ($modifierModifiers as $modifyingModifierValue => $modifierCode) { ?>
                        <label>
                            <input name="modifiersOfModifiers[<?= $tier ?>][<?= $modifiedModifierValue ?>]" type="checkbox" value="<?= $modifierCode ?>">
                            <?= /** @var ModifierCode $modifierCode */
                            $modifierCode->translateTo('cs') ?>
                        </label>
                    <?php }
                } ?>
                <button type="submit">Vybrat modifikátory úrovně <?= $tier ?></button>
            </div>
        <?php } ?>
    <?php } ?>
</form>
</body>
</html>